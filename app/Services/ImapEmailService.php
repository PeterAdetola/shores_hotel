<?php

namespace App\Services;

use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;
use Illuminate\Support\Facades\Log;

class ImapEmailService
{
    /**
     * Get IMAP client for the specified account
     *
     * @param string $account The account name from config (default, booking_hotel, booking_apartment)
     * @return Client
     */
    public static function getClient(string $account = 'default'): Client
    {
        try {
            $cm = new ClientManager(config('imap'));
            $accountConfig = config("imap.accounts.{$account}");

            if (!$accountConfig) {
                throw new \Exception("IMAP account '{$account}' not found in config");
            }

            // Use make() method with explicit config - this is what fixed the localhost issue
            return $cm->make([
                'host' => $accountConfig['host'],
                'port' => $accountConfig['port'],
                'protocol' => $accountConfig['protocol'],
                'encryption' => $accountConfig['encryption'],
                'validate_cert' => $accountConfig['validate_cert'],
                'username' => $accountConfig['username'],
                'password' => $accountConfig['password'],
                'timeout' => $accountConfig['timeout'] ?? 30,
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to create IMAP client for account '{$account}': " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch emails from a specific account
     *
     * @param string $account Account name (default, booking_hotel, booking_apartment)
     * @param string $folderName Folder name (INBOX, Sent, etc.)
     * @param int $limit Number of emails to fetch
     * @param bool $unreadOnly Fetch only unread emails
     * @return \Illuminate\Support\Collection
     */
    public static function fetchEmails(
        string $account = 'default',
        string $folderName = 'INBOX', // ← Changed parameter name
        int $limit = 50,
        bool $unreadOnly = false
    ) {
        try {
            $client = self::getClient($account);
            $client->connect();

            Log::info("Connected to IMAP for account: {$account}");

            $folder = $client->getFolder($folderName); // ← Using folderName here

            $query = $folder->messages();

            if ($unreadOnly) {
                $query->unseen();
            }

            $messages = $query->all()->limit($limit)->get();

            // Fixed: Use $folderName (string) instead of $folder (object)
            Log::info("Fetched " . $messages->count() . " emails from {$account}/{$folderName}");

            $client->disconnect();

            return $messages;

        } catch (\Exception $e) {
            Log::error("Failed to fetch emails from {$account}/{$folderName}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all folders for an account
     *
     * @param string $account
     * @return \Illuminate\Support\Collection
     */
    public static function getFolders(string $account = 'default')
    {
        try {
            $client = self::getClient($account);
            $client->connect();

            $folders = $client->getFolders();

            $client->disconnect();

            return $folders;

        } catch (\Exception $e) {
            Log::error("Failed to get folders for {$account}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mark email as read
     *
     * @param string $account
     * @param string $folder
     * @param int $uid Email UID
     * @return bool
     */
    public static function markAsRead(string $account, string $folder, int $uid): bool
    {
        try {
            $client = self::getClient($account);
            $client->connect();

            $folder = $client->getFolder($folder);
            $message = $folder->messages()->getMessageByUid($uid);

            if ($message) {
                $message->setFlag('Seen');
                $client->disconnect();
                return true;
            }

            $client->disconnect();
            return false;

        } catch (\Exception $e) {
            Log::error("Failed to mark email as read: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread count for an account
     *
     * @param string $account
     * @param string $folder
     * @return int
     */
    public static function getUnreadCount(string $account = 'default', string $folder = 'INBOX'): int
    {
        try {
            $client = self::getClient($account);
            $client->connect();

            $folder = $client->getFolder($folder);
            $count = $folder->messages()->unseen()->count();

            $client->disconnect();

            return $count;

        } catch (\Exception $e) {
            Log::error("Failed to get unread count for {$account}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Test connection for an account
     *
     * @param string $account
     * @return array
     */
    public static function testConnection(string $account = 'default'): array
    {
        try {
            $client = self::getClient($account);
            $client->connect();

            $folders = $client->getFolders();

            $client->disconnect();

            return [
                'success' => true,
                'message' => "Successfully connected to {$account}",
                'folders_count' => $folders->count(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Failed to connect: " . $e->getMessage(),
            ];
        }
    }
}
