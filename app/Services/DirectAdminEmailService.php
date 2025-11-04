<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DirectAdminEmailService
{
    protected $daUrl;
    protected $daUsername;
    protected $daPassword;
    protected $domain;

    public function __construct()
    {
        $this->daUrl = config('directadmin.url');
        $this->daUsername = config('directadmin.username');
        $this->daPassword = config('directadmin.password');
        $this->domain = config('directadmin.domain');
    }

    /**
     * Fetch all email accounts for the domain
     */
    public function getEmailAccounts()
    {
        // Cache for 1 hour to avoid too many API calls
        return Cache::remember('directadmin_email_accounts', 3600, function () {
            try {
                Log::info('Fetching email accounts from DirectAdmin');

                // DirectAdmin uses CMD_API_POP endpoint
                $response = Http::withBasicAuth($this->daUsername, $this->daPassword)
                    ->withoutVerifying() // Add this to avoid SSL issues
                    ->timeout(30)
                    ->get($this->daUrl . '/CMD_API_POP', [
                        'action' => 'list',
                        'domain' => $this->domain
                    ]);

                if ($response->successful()) {
                    $body = $response->body();

                    Log::info('DirectAdmin response received', ['status' => $response->status()]);

                    // DirectAdmin returns URL-encoded format
                    parse_str($body, $data);

                    if (isset($data['list']) && is_array($data['list'])) {
                        $accounts = [];

                        foreach ($data['list'] as $username) {
                            if (!empty($username)) {
                                $fullEmail = $username . '@' . $this->domain;

                                // Get quota info for each account
                                $quotaInfo = $this->getEmailQuota($username);

                                $accounts[] = [
                                    'email' => $fullEmail,
                                    'user' => $username,
                                    'domain' => $this->domain,
                                    'quota' => $quotaInfo['quota'] ?? 'unlimited',
                                    'usage' => $quotaInfo['usage'] ?? 0,
                                ];
                            }
                        }

                        Log::info('Found ' . count($accounts) . ' email accounts');
                        return $accounts;
                    }

                    Log::warning('DirectAdmin response invalid', ['data' => $data]);
                    return [];
                }

                Log::error('DirectAdmin API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];

            } catch (\Exception $e) {
                Log::error('DirectAdmin API error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get email quota information
     */
    private function getEmailQuota($username)
    {
        try {
            $response = Http::withBasicAuth($this->daUsername, $this->daPassword)
                ->withoutVerifying()
                ->timeout(10)
                ->get($this->daUrl . '/CMD_API_POP', [
                    'action' => 'modify',
                    'domain' => $this->domain,
                    'user' => $username
                ]);

            if ($response->successful()) {
                parse_str($response->body(), $data);
                return [
                    'quota' => $data['quota'] ?? 'unlimited',
                    'usage' => $data['usage'] ?? 0,
                ];
            }

            return ['quota' => 'unlimited', 'usage' => 0];

        } catch (\Exception $e) {
            return ['quota' => 'unlimited', 'usage' => 0];
        }
    }



    /**
     * Clear the email accounts cache
     */
    public function clearCache()
    {
        Cache::forget('directadmin_email_accounts');
        Log::info('Email accounts cache cleared');
    }

    /**
     * Test DirectAdmin connection
     */
    public function testConnection()
    {
        try {
            $response = Http::withBasicAuth($this->daUsername, $this->daPassword)
                ->timeout(10)
                ->get($this->daUrl . '/CMD_API_POP', [
                    'action' => 'list',
                    'domain' => $this->domain
                ]);

            $body = $response->body();
            parse_str($body, $data);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful() ? 'Connection successful' : 'Connection failed',
                'data' => $data,
                'raw_response' => $body
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a new email account
     */
    public function createEmailAccount($username, $password, $quota = 'unlimited')
    {
        try {
            $response = Http::withBasicAuth($this->daUsername, $this->daPassword)
                ->timeout(30)
                ->post($this->daUrl . '/CMD_API_POP', [
                    'action' => 'create',
                    'domain' => $this->domain,
                    'user' => $username,
                    'passwd' => $password,
                    'passwd2' => $password,
                    'quota' => $quota,
                ]);

            parse_str($response->body(), $data);

            return [
                'success' => isset($data['error']) && $data['error'] == '0',
                'message' => $data['text'] ?? 'Unknown error',
                'data' => $data
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete an email account
     */
    public function deleteEmailAccount($username)
    {
        try {
            $response = Http::withBasicAuth($this->daUsername, $this->daPassword)
                ->timeout(30)
                ->post($this->daUrl . '/CMD_API_POP', [
                    'action' => 'delete',
                    'domain' => $this->domain,
                    'user' => $username,
                ]);

            parse_str($response->body(), $data);

            return [
                'success' => isset($data['error']) && $data['error'] == '0',
                'message' => $data['text'] ?? 'Unknown error',
                'data' => $data
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
