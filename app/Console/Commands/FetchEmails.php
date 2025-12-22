<?php

// app/Console/Commands/FetchEmails.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImapEmailService;

class FetchEmails extends Command
{
    protected $signature = 'emails:fetch {account=default}';
    protected $description = 'Fetch emails from IMAP';

    public function handle()
    {
        $account = $this->argument('account');

        $this->info("Fetching emails from {$account}...");

        try {
            $emails = ImapEmailService::fetchEmails($account, 'INBOX', 10);

            $this->info("Found {$emails->count()} emails");

            foreach ($emails as $email) {
                $this->line("- {$email->getSubject()} (from: {$email->getFrom()[0]->mail})");
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
