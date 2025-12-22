<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\PHPIMAP\ClientManager;

class TestEmailConnection extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Test email IMAP connection';

    public function handle()
    {
        $this->info('Starting email connection test...');
        $this->line('');

        try {
            // Get the full imap config
            $imapConfig = config('imap');
            $accountConfig = config('imap.accounts.default');

            $this->info('Configuration from config:');
            $this->line('Host: ' . $accountConfig['host']);
            $this->line('Port: ' . $accountConfig['port']);
            $this->line('Username: ' . $accountConfig['username']);
            $this->line('Password: ' . (strlen($accountConfig['password']) > 0 ? 'Set (' . strlen($accountConfig['password']) . ' chars)' : 'EMPTY'));
            $this->line('');

            // Use ClientManager but check the config it loads
            $cm = new ClientManager($imapConfig);

            $this->info('Trying to get account from ClientManager...');
            $client = $cm->make([
                'host' => $accountConfig['host'],
                'port' => $accountConfig['port'],
                'protocol' => $accountConfig['protocol'],
                'encryption' => $accountConfig['encryption'],
                'validate_cert' => $accountConfig['validate_cert'],
                'username' => $accountConfig['username'],
                'password' => $accountConfig['password'],
                'timeout' => $accountConfig['timeout'] ?? 30,
            ]);

            $this->info('Client created. Properties:');
            $this->line('Client Host: ' . $client->host);
            $this->line('Client Port: ' . $client->port);
            $this->line('Client Username: ' . $client->username);
            $this->line('');

            $this->info('Attempting to connect...');
            $client->connect();
            $this->info('✓ Connected successfully!');

            $folders = $client->getFolders();
            $this->info('✓ Folders found: ' . $folders->count());

            $inbox = $client->getFolder('INBOX');
            $messages = $inbox->messages()->all()->limit(1)->get();
            $this->info('✓ Messages in INBOX: ' . $messages->count());

            $client->disconnect();
            $this->info('✓ All tests passed!');

            return 0;

        } catch (\Exception $e) {
            $this->error('✗ Connection failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');

            if ($e->getPrevious()) {
                $this->error('Previous Error: ' . $e->getPrevious()->getMessage());
            }

            $this->line('');
            $this->error('Stack trace:');
            $this->line($e->getTraceAsString());

            return 1;
        }
    }
}
