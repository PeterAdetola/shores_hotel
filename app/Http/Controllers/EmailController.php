<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEmail;
use Carbon\Carbon;
use App\Services\DirectAdminEmailService;
use App\Models\EmailAccount;

class EmailController extends Controller
{
    protected $client;
    protected $daService;
    protected $clientManager;

    public function __construct()
    {
        $this->clientManager = new ClientManager();
        $this->clientManager = new ClientManager();
        $this->daService = new DirectAdminEmailService();
    }

    /**
     * Get all email accounts from DirectAdmin
     */
    public function getEmailAccounts()
    {
        // Get from DirectAdmin API
        $daAccounts = $this->daService->getEmailAccounts();

        // Merge with database accounts (which have passwords)
        $dbAccounts = EmailAccount::getActive();

        // Map DirectAdmin accounts with database info
        $accounts = collect($daAccounts)->map(function ($daAccount) use ($dbAccounts) {
            $dbAccount = $dbAccounts->firstWhere('email', $daAccount['email']);

            return [
                'email' => $daAccount['email'],
                'display_name' => $dbAccount->display_name ?? $daAccount['email'],
                'has_password' => $dbAccount ? true : false,
                'is_active' => $dbAccount ? $dbAccount->is_active : false,
                'quota' => $daAccount['quota'] ?? 'unlimited',
                'usage' => $daAccount['usage'] ?? 0,
            ];
        })->toArray();

        return $accounts;
    }

    /**
     * Test DirectAdmin connection
     */
    public function testDirectAdminConnection()
    {
        $result = $this->daService->testConnection();

        return response()->json($result);
    }

    /**
     * Clear email accounts cache
     */
    public function clearEmailCache()
    {
        $this->daService->clearCache();

        return redirect()->back()->with('success', 'Email accounts cache cleared');
    }

    protected function getClient()
    {
        if (!$this->client) {
            try {
                $this->client = $this->clientManager->make([
                    'host' => 'mail.jupitercorporateservices.com',
                    'port' => 993,
                    'encryption' => 'ssl',
                    'validate_cert' => false,
                    'username' => 'hello@shoreshotelng.com',
                    'password' => 'hello@shoresEmailLogin',
                    'protocol' => 'imap',
                    'authentication' => null,
                ]);

                $this->client->connect();
                \Log::info("IMAP connection established successfully");

            } catch (\Exception $e) {
                \Log::error("IMAP Connection Failed: " . $e->getMessage());
                throw new \Exception("Email connection failed: " . $e->getMessage());
            }
        }

        return $this->client;
    }

    /**
     * Get the correct IMAP folder name based on the folder type
     */
    private function getImapFolderName($folder, $client)
    {
        if ($folder === 'INBOX') {
            return 'INBOX';
        }

        // Try different folder name formats - expanded list
        $folderMappings = [
            'SENT' => [
                'INBOX.Sent',           // cPanel/WHM format
                'INBOX.Sent Items',     // Some cPanel setups
                'Sent',                 // Standard
                'Sent Items',           // Outlook
                'Sent Messages',        // Some providers
                '[Gmail]/Sent Mail',    // Gmail
                'Sent Mail',            // Alternative
            ],
            'DRAFT' => [
                'INBOX.Drafts',
                'Drafts',
                '[Gmail]/Drafts',
                'Draft',
            ],
            'SPAM' => [
                'INBOX.Spam',
                'INBOX.Junk',
                'Spam',
                '[Gmail]/Spam',
                'Junk',
                'Junk Email',
                'Junk E-mail',
            ],
            'TRASH' => [
                'INBOX.Trash',
                'Trash',
                '[Gmail]/Trash',
                'Deleted Items',
                'Deleted Messages',
                'Deleted',
            ],
        ];

        if (!isset($folderMappings[$folder])) {
            \Log::warning("Unknown folder type: {$folder}");
            return 'INBOX';
        }

        // Get all available folders first
        try {
            $allFolders = $client->getFolders();
            $availableFolderNames = [];

            foreach ($allFolders as $f) {
                $availableFolderNames[] = $f->full_name;
            }

            \Log::info("Available folders: " . implode(', ', $availableFolderNames));
        } catch (\Exception $e) {
            \Log::error("Could not list folders: " . $e->getMessage());
        }

        // Try each possible folder name
        foreach ($folderMappings[$folder] as $possibleName) {
            try {
                $testFolder = $client->getFolder($possibleName);
                if ($testFolder) {
                    \Log::info("✓ Found folder: '{$possibleName}' for {$folder}");
                    return $possibleName;
                }
            } catch (\Exception $e) {
                \Log::debug("✗ Folder '{$possibleName}' not found");
                continue;
            }
        }

        \Log::warning("Could not find folder for {$folder}, defaulting to INBOX");
        return 'INBOX';
    }

    /**
     * Switch to email account and view inbox
     */
    public function switchAndView(Request $request, $email)
    {
        try {
            // Verify account exists and has password
            $account = EmailAccount::where('email', $email)->where('is_active', true)->first();

            if (!$account) {
                return redirect()->route('admin.email.inbox')
                    ->with('error', 'Email account not found or not configured');
            }

            // Store selected email in session
            session(['active_email' => $email]);

            \Log::info("Switched to email account: {$email}");

            // Redirect to inbox with the selected account
            return redirect()->route('admin.email.inbox')
                ->with('success', "Now viewing emails for " . $account->display_name);

        } catch (\Exception $e) {
            \Log::error("Error switching account: " . $e->getMessage());

            return redirect()->route('admin.email.inbox')
                ->with('error', 'Failed to switch email account');
        }
    }

    /**
     * Display inbox with emails from any folder
     */
    public function inbox(Request $request, $folder = null)
    {
        $folder = $folder ?: $request->get('folder', 'INBOX');

        // Get active email from session or default
        $defaultAccount = EmailAccount::getDefault();
        $activeEmail = session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');

        try {
            // IMPORTANT: Connect with the selected email account
            $client = $this->getClientForEmail($activeEmail);

            $imapFolderName = $this->getImapFolderName($folder, $client);
            $oFolder = $client->getFolder($imapFolderName);

            \Log::info("=== INBOX METHOD START ===");
            \Log::info("Active Email: " . $activeEmail);
            \Log::info("Requested Folder: " . $folder);
            \Log::info("IMAP Folder: " . $imapFolderName);

            $messages = $oFolder->messages()->all()->get()->sortByDesc(function($message) {
                return $message->getDate();
            });

            \Log::info("Messages fetched: " . $messages->count());

            $emails = [];
            foreach ($messages as $message) {
                try {
                    $subjectAttr = $message->getSubject();
                    $subject = '(No Subject)';
                    if ($subjectAttr) {
                        $subject = $subjectAttr->toString();
                        if (empty(trim($subject))) {
                            $subject = '(No Subject)';
                        }
                    }

                    if ($folder === 'SENT') {
                        $to = $message->getTo();
                        $displayAddress = 'Unknown';
                        $displayName = 'Unknown';

                        if (!empty($to) && isset($to[0])) {
                            $displayAddress = $to[0]->mail ?? 'Unknown';
                            $displayName = $to[0]->personal ?? $displayAddress;
                        }
                    } else {
                        $from = $message->getFrom();
                        $displayAddress = 'Unknown';
                        $displayName = 'Unknown';

                        if (!empty($from) && isset($from[0])) {
                            $displayAddress = $from[0]->mail ?? 'Unknown';
                            $displayName = $from[0]->personal ?? $displayAddress;
                        }
                    }

                    $dateAttr = $message->getDate();
                    $dateString = 'No date';
                    if ($dateAttr) {
                        $dateString = $dateAttr->toString();
                    }

                    // FIXED: Get email preview properly
                    $preview = 'No content';
                    try {
                        $preview = $this->getEmailPreview($message, 100);
                    } catch (\Exception $e) {
                        \Log::warning("Preview error: " . $e->getMessage());
                        // Try fallback to text body
                        try {
                            if ($message->hasTextBody()) {
                                $textBody = $message->getTextBody();
                                if ($textBody) {
                                    $preview = substr(strip_tags($textBody), 0, 100) . '...';
                                }
                            }
                        } catch (\Exception $e2) {
                            // Ignore
                        }
                    }

                    $isSeen = false;
                    $isFlagged = false;
                    try {
                        $flags = $message->getFlags();
                        $flagsString = (string) $flags;

                        $isSeen = str_contains($flagsString, 'Seen');
                        $isFlagged = str_contains($flagsString, 'Flagged');
                    } catch (\Exception $e) {
                        \Log::warning("Could not get flags for message: " . $e->getMessage());
                    }

                    $emailData = [
                        'uid' => $message->getUid(),
                        'subject' => $subject,
                        'from_name' => $displayName,
                        'from' => $displayAddress,
                        'date' => $dateString,
                        'has_attachments' => $message->hasAttachments(),
                        'is_seen' => $isSeen,
                        'is_flagged' => $isFlagged,
                        'preview' => $preview,
                        'folder' => $folder,
                    ];

                    $emails[] = $emailData;

                } catch (\Exception $e) {
                    \Log::error("Error processing message: " . $e->getMessage());
                    continue;
                }
            }

            \Log::info("=== INBOX METHOD END ===");
            \Log::info("Total emails to display: " . count($emails));

            // Get email accounts for sidebar
            $emailAccounts = $this->getEmailAccounts();

            return view('admin.email.inbox', compact('emails', 'folder', 'emailAccounts', 'activeEmail'));

        } catch (\Exception $e) {
            \Log::error("Email inbox error for {$activeEmail}: " . $e->getMessage());

            $emails = [];
            $emailAccounts = $this->getEmailAccounts();
            $error = 'Unable to fetch emails for ' . $activeEmail . ': ' . $e->getMessage();

            return view('admin.email.inbox', compact('emails', 'folder', 'emailAccounts', 'activeEmail', 'error'));
        }
    }

    /**
     * Get email preview text
     */
    private function getEmailPreview($message, $length = 100)
    {
        try {
            $text = '';

            // Try to get plain text body first
            if ($message->hasTextBody()) {
                $text = $message->getTextBody();
            }

            // If no plain text or too short, extract from HTML
            if (empty(trim($text)) && $message->hasHTMLBody()) {
                $html = $message->getHTMLBody();
                if (!empty($html)) {
                    // Strip HTML tags but preserve line breaks
                    $text = strip_tags($html);
                    // Decode HTML entities
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }

            // Clean up whitespace and normalize
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);

            // If still empty, return default
            if (empty($text)) {
                return 'No content';
            }

            // Truncate to desired length
            if (mb_strlen($text) > $length) {
                $text = mb_substr($text, 0, $length) . '...';
            }

            return $text;

        } catch (\Exception $e) {
            \Log::error('Error getting email preview: ' . $e->getMessage());
            return 'No content';
        }
    }

    /**
     * Get IMAP client for specific email account
     * IMPORTANT: This resets the connection for each account
     */
    protected function getClientForEmail($email)
    {
        // CRITICAL: Reset the client to force new connection
        $this->client = null;

        try {
            // Get account from database
            $account = EmailAccount::where('email', $email)->where('is_active', true)->first();

            if (!$account) {
                throw new \Exception("Email account {$email} not found or not active");
            }

            \Log::info("Connecting to IMAP for: {$email}");

            // Create new client with account-specific credentials
            $this->client = $this->clientManager->make([
                'host' => 'mail.jupitercorporateservices.com',
                'port' => 993,
                'encryption' => 'ssl',
                'validate_cert' => false,
                'username' => $account->username,
                'password' => $account->password, // Automatically decrypted by model
                'protocol' => 'imap',
                'authentication' => null,
            ]);

            $this->client->connect();
            \Log::info("✓ IMAP connection established for: {$email}");

            return $this->client;

        } catch (\Exception $e) {
            \Log::error("✗ IMAP Connection Failed for {$email}: " . $e->getMessage());
            throw new \Exception("Failed to connect to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Display single email
     */
    public function show(Request $request, $uid)
    {
        try {
            // Get active email from session
            $defaultAccount = EmailAccount::getDefault();
            $activeEmail = session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');

            // IMPORTANT: Use the active account's credentials
            $client = $this->getClientForEmail($activeEmail);

            // Get folder from request or default to INBOX
            $folderParam = $request->get('folder', 'INBOX');
            $imapFolderName = $this->getImapFolderName($folderParam, $client);
            $imapFolder = $client->getFolder($imapFolderName);

            $message = $imapFolder->query()->uid($uid)->get()->first();

            if (!$message) {
                throw new \Exception("Message with UID {$uid} not found");
            }

            // Get message details
            $subjectAttr = $message->getSubject();
            $subject = $subjectAttr ? $subjectAttr->toString() : '(No Subject)';

            $from = $message->getFrom();
            $fromAddress = !empty($from) && isset($from[0]) ? $from[0]->mail : 'Unknown';
            $fromName = !empty($from) && isset($from[0]) ? ($from[0]->personal ?? $fromAddress) : 'Unknown';

            $dateAttr = $message->getDate();
            $dateString = $dateAttr ? $dateAttr->toString() : 'No date';

            // FIXED: Get message body with aggressive HTML handling
            $body = '';
            try {
                // Try to get the raw body first
                $rawBody = '';

                if ($message->hasHTMLBody()) {
                    $rawBody = $message->getHTMLBody();
                } elseif ($message->hasTextBody()) {
                    $rawBody = $message->getTextBody();
                }

                // DIAGNOSTIC: Log what we received
                \Log::info("=== EMAIL BODY DIAGNOSTIC ===");
                \Log::info("UID: {$uid}");
                \Log::info("Has style attributes: " . (strpos($rawBody, 'style=') !== false ? 'YES' : 'NO'));
                \Log::info("Body preview (first 1000 chars): " . substr($rawBody, 0, 1000));

                if (!empty($rawBody)) {
                    // Aggressive decoding - handle multiple levels of encoding
                    $decoded = $rawBody;
                    $iterations = 0;
                    $maxIterations = 5;

                    // Keep decoding until no more HTML entities found or max iterations reached
                    while ($iterations < $maxIterations &&
                        (strpos($decoded, '&lt;') !== false ||
                            strpos($decoded, '&gt;') !== false ||
                            strpos($decoded, '&quot;') !== false ||
                            strpos($decoded, '&#') !== false)) {

                        $before = $decoded;
                        $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                        // If nothing changed, break to prevent infinite loop
                        if ($before === $decoded) {
                            break;
                        }

                        $iterations++;
                    }

                    // DIAGNOSTIC: Log after decoding
                    \Log::info("After decoding - Has style attributes: " . (strpos($decoded, 'style=') !== false ? 'YES' : 'NO'));

                    // Now check if we have valid HTML
                    if ($this->looksLikeHTML($decoded)) {
                        $body = $this->sanitizeEmailBody($decoded);

                        // DIAGNOSTIC: Log after sanitization
                        \Log::info("After sanitization - Has style attributes: " . (strpos($body, 'style=') !== false ? 'YES' : 'NO'));
                        \Log::info("Sanitized body preview (first 1000 chars): " . substr($body, 0, 1000));
                    } else {
                        // Still plain text, format it
                        $body = '<pre style="white-space: pre-wrap; font-family: Arial, sans-serif; background: #f5f5f5; padding: 15px; border-radius: 5px;">'
                            . htmlspecialchars($decoded)
                            . '</pre>';
                    }
                } else {
                    $body = '<p class="grey-text center-align">No content available</p>';
                }
            } catch (\Exception $e) {
                $body = '<p class="red-text">Unable to load message content.</p>';
                \Log::error("Error loading email body for UID {$uid}: " . $e->getMessage());
            }

            // Mark as seen
            try {
                $flags = $message->getFlags();
                $flagsString = (string) $flags;
                if (strpos($flagsString, 'Seen') === false) {
                    $message->setFlag('Seen');
                }
            } catch (\Exception $e) {
                \Log::warning("Could not mark message as seen: " . $e->getMessage());
            }

            // Pass folder to view for sidebar
            $folder = $folderParam;
            $emailAccounts = $this->getEmailAccounts();

            return view('admin.email.show', compact('message', 'subject', 'fromName', 'fromAddress', 'dateString', 'body', 'folder', 'emailAccounts', 'activeEmail'));

        } catch (\Exception $e) {
            \Log::error("Email show error: " . $e->getMessage());
            return back()->with('error', 'Unable to load email: ' . $e->getMessage());
        }
    }

    /**
     * Check if a string looks like HTML content
     */
    private function looksLikeHTML($text)
    {
        // Check for common HTML patterns
        $htmlPatterns = [
            '/<(!DOCTYPE|html|head|body|div|p|span|table|tr|td|h[1-6]|style)/i',
            '/&lt;(!DOCTYPE|html|head|body|div|p)/i'
        ];

        foreach ($htmlPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sanitize email body to prevent XSS attacks while keeping formatting
     */
    private function sanitizeEmailBody($html)
    {
        if (empty($html)) {
            return $html;
        }

        try {
            // Use AntiXSS if available
            if (class_exists('voku\helper\AntiXSS')) {
                $antiXss = new \voku\helper\AntiXSS();
                $cleanHtml = $antiXss->xss_clean($html);

                // AntiXSS might be too aggressive, so if it returns empty, fallback
                if (!empty(trim(strip_tags($cleanHtml)))) {
                    return $cleanHtml;
                }
            }
        } catch (\Exception $e) {
            \Log::warning("AntiXSS failed: " . $e->getMessage());
        }

        // Fallback to improved basic sanitization
        return $this->basicSanitizeEmailBody($html);
    }

    private function basicSanitizeEmailBody($html)
    {
        // Allow safe HTML tags for email display including style tags and meta
        $allowedTags = '<html><head><title><meta><style><body><div><p><span><br><hr><h1><h2><h3><h4><h5><h6><strong><b><em><i><u><a><img><table><tr><td><th><thead><tbody><tfoot><ul><ol><li><blockquote><pre><code><sup><sub><small><big><font><center>';

        $html = strip_tags($html, $allowedTags);

        // Remove dangerous scripts and iframes
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);
        $html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $html);
        $html = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is', '', $html);
        $html = preg_replace('/<form\b[^>]*>(.*?)<\/form>/is', '', $html);

        // Remove event handlers (more comprehensive)
        $html = preg_replace('/\s*on\w+\s*=\s*(["\'][^"\']*["\']|[^\s>]+)/i', '', $html);

        // Remove dangerous protocols from links and src
        $html = preg_replace('/\s+href\s*=\s*["\']?\s*(javascript|data|vbscript):/i', ' href="#"', $html);
        $html = preg_replace('/\s+src\s*=\s*["\']?\s*(javascript|data|vbscript):/i', ' src="#"', $html);

        // Remove meta refresh and other dangerous meta tags
        $html = preg_replace('/<meta[^>]*http-equiv\s*=\s*["\']?refresh["\']?[^>]*>/i', '', $html);

        // Remove style attributes that could be used for attacks (but keep style tags)
        $html = preg_replace('/\s+style\s*=\s*["\'][^"\']*expression\([^"\']*\)[^"\']*["\']/i', '', $html);

        return $html;
    }

    /**
     * Compose new email
     */
    public function compose(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'to' => 'required|email',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
                'cc' => 'nullable|email',
                'bcc' => 'nullable|email',
                'attachments.*' => 'nullable|file|max:10240',
            ]);

            try {
                // Get active email account
                $defaultAccount = EmailAccount::getDefault();
                $activeEmail = session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');
                $account = EmailAccount::where('email', $activeEmail)->first();

                // Send the email using the active account's "from" address
                Mail::send([], [], function ($mail) use ($validated, $request, $account) {
                    $mail->from($account->email, $account->display_name ?? $account->email);
                    $mail->to($validated['to'])
                        ->subject($validated['subject'])
                        ->html($validated['message']);

                    if (!empty($validated['cc'])) {
                        $mail->cc($validated['cc']);
                    }

                    if (!empty($validated['bcc'])) {
                        $mail->bcc($validated['bcc']);
                    }

                    if ($request->hasFile('attachments')) {
                        foreach ($request->file('attachments') as $file) {
                            $mail->attach($file->getRealPath(), [
                                'as' => $file->getClientOriginalName(),
                                'mime' => $file->getMimeType(),
                            ]);
                        }
                    }
                });

                // Save to sent folder for the active account
                try {
                    $this->saveToSentFolderForAccount($validated, $request, $activeEmail);
                    \Log::info("Email saved to Sent folder for {$activeEmail}");
                } catch (\Exception $e) {
                    \Log::error("Could not save to Sent folder for {$activeEmail}: " . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully from ' . $account->display_name . '!'
                ]);

            } catch (\Exception $e) {
                \Log::error("Email send error: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error sending email: ' . $e->getMessage()
                ], 500);
            }
        }

        return view('admin.email.inbox');
    }

    /**
     * Save to Sent folder for specific account
     */
    private function saveToSentFolderForAccount($emailData, $request, $activeEmail)
    {
        try {
            $account = EmailAccount::where('email', $activeEmail)->first();

            // Get email configuration for active account
            $host = 'mail.jupitercorporateservices.com';
            $port = 993;
            $username = $account->username;
            $password = $account->password;

            // Build mailbox connection string for Sent folder
            $mailbox = "{{$host}:{$port}/imap/ssl/novalidate-cert}Sent";

            \Log::info("Connecting to Sent folder for: {$activeEmail}");

            // Connect to IMAP
            $imap = @imap_open($mailbox, $username, $password);

            if (!$imap) {
                throw new \Exception("Could not connect to IMAP: " . imap_last_error());
            }

            // Build email message
            $from = $account->email;
            $fromName = $account->display_name ?? $account->email;
            $to = $emailData['to'];
            $subject = $emailData['subject'];
            $htmlBody = $emailData['message'];
            $date = date('r');

            // Create boundary for multipart
            $boundary = "----=_Part_" . md5(uniqid(time()));

            // Build message parts
            $parts = [];
            $parts[] = "From: {$fromName} <{$from}>";
            $parts[] = "To: {$to}";

            if (!empty($emailData['cc'])) {
                $parts[] = "Cc: {$emailData['cc']}";
            }

            if (!empty($emailData['bcc'])) {
                $parts[] = "Bcc: {$emailData['bcc']}";
            }

            $parts[] = "Subject: {$subject}";
            $parts[] = "Date: {$date}";
            $parts[] = "MIME-Version: 1.0";
            $parts[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
            $parts[] = "";
            $parts[] = "This is a multi-part message in MIME format.";
            $parts[] = "";

            // Plain text part
            $plainText = strip_tags($htmlBody);
            $parts[] = "--{$boundary}";
            $parts[] = "Content-Type: text/plain; charset=UTF-8";
            $parts[] = "Content-Transfer-Encoding: 8bit";
            $parts[] = "";
            $parts[] = $plainText;
            $parts[] = "";

            // HTML part
            $parts[] = "--{$boundary}";
            $parts[] = "Content-Type: text/html; charset=UTF-8";
            $parts[] = "Content-Transfer-Encoding: 8bit";
            $parts[] = "";
            $parts[] = $htmlBody;
            $parts[] = "";
            $parts[] = "--{$boundary}--";

            // Join all parts
            $message = implode("\r\n", $parts);

            \Log::info("Attempting to append message to Sent folder for {$activeEmail}");

            // Append to Sent folder
            $result = imap_append($imap, $mailbox, $message, "\\Seen");

            if (!$result) {
                throw new \Exception("imap_append failed: " . imap_last_error());
            }

            imap_close($imap);

            \Log::info("✓ Successfully saved to Sent folder for {$activeEmail}");

        } catch (\Exception $e) {
            \Log::error("✗ Failed to save to Sent folder for {$activeEmail}: " . $e->getMessage());
            // Don't throw - we don't want to fail the email send if saving to Sent fails
        }
    }

    /**
     * Save sent email to IMAP Sent folder - SIMPLIFIED VERSION
     */
    private function saveToSentFolder($emailData, $request)
    {
        try {
            // Get email configuration
            $host = 'mail.jupitercorporateservices.com';
            $port = 993;
            $username = 'hello@shoreshotelng.com';
            $password = 'hello@shoresEmailLogin';

            // Build mailbox connection string for Sent folder
            $mailbox = "{{$host}:{$port}/imap/ssl/novalidate-cert}Sent";

            \Log::info("Connecting to mailbox: {$mailbox}");

            // Connect to IMAP
            $imap = @imap_open($mailbox, $username, $password);

            if (!$imap) {
                throw new \Exception("Could not connect to IMAP: " . imap_last_error());
            }

            // Build email message
            $from = config('mail.from.address', $username);
            $fromName = config('mail.from.name', 'Shores Hotel');
            $to = $emailData['to'];
            $subject = $emailData['subject'];
            $htmlBody = $emailData['message'];
            $date = date('r');

            // Create boundary for multipart
            $boundary = "----=_Part_" . md5(uniqid(time()));

            // Build message parts
            $parts = [];
            $parts[] = "From: {$fromName} <{$from}>";
            $parts[] = "To: {$to}";

            if (!empty($emailData['cc'])) {
                $parts[] = "Cc: {$emailData['cc']}";
            }

            if (!empty($emailData['bcc'])) {
                $parts[] = "Bcc: {$emailData['bcc']}";
            }

            $parts[] = "Subject: {$subject}";
            $parts[] = "Date: {$date}";
            $parts[] = "MIME-Version: 1.0";
            $parts[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
            $parts[] = "";
            $parts[] = "This is a multi-part message in MIME format.";
            $parts[] = "";

            // Plain text part
            $plainText = strip_tags($htmlBody);
            $parts[] = "--{$boundary}";
            $parts[] = "Content-Type: text/plain; charset=UTF-8";
            $parts[] = "Content-Transfer-Encoding: 8bit";
            $parts[] = "";
            $parts[] = $plainText;
            $parts[] = "";

            // HTML part
            $parts[] = "--{$boundary}";
            $parts[] = "Content-Type: text/html; charset=UTF-8";
            $parts[] = "Content-Transfer-Encoding: 8bit";
            $parts[] = "";
            $parts[] = $htmlBody;
            $parts[] = "";
            $parts[] = "--{$boundary}--";

            // Join all parts
            $message = implode("\r\n", $parts);

            \Log::info("Attempting to append message to Sent folder");
            \Log::info("Subject: {$subject}");
            \Log::info("Message length: " . strlen($message));

            // Append to Sent folder
            $result = imap_append($imap, $mailbox, $message, "\\Seen");

            if (!$result) {
                throw new \Exception("imap_append failed: " . imap_last_error());
            }

            imap_close($imap);

            \Log::info("✓ Successfully saved to Sent folder");

        } catch (\Exception $e) {
            \Log::error("✗ Failed to save to Sent folder: " . $e->getMessage());
            // Don't throw - we don't want to fail the email send if saving to Sent fails
        }
    }

    /**
     * Reply to email
     */
    public function reply(Request $request, $uid)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $client = $this->getClient();
            $folder = $client->getFolder('INBOX');
            $originalMessage = $folder->query()->uid($uid)->get()->first();

            if (!$originalMessage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Original message not found'
                ], 404);
            }

            $from = $originalMessage->getFrom();
            $to = !empty($from) && isset($from[0]) ? $from[0]->mail : null;

            if (!$to) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not determine recipient'
                ], 400);
            }

            $subjectAttr = $originalMessage->getSubject();
            $originalSubject = $subjectAttr ? $subjectAttr->toString() : '';
            $subject = 'Re: ' . $originalSubject;

            Mail::send([], [], function ($mail) use ($to, $subject, $validated) {
                $mail->to($to)
                    ->subject($subject)
                    ->html($validated['message']);
            });

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending reply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forward email
     */
    public function forward(Request $request, $uid)
    {
        try {
            $client = $this->getClient();
            $folder = $client->getFolder('INBOX');

            $originalMessage = $folder->query()->uid($uid)->get()->first();

            if (!$originalMessage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Original message not found'
                ], 404);
            }

            $to = $request->input('to');
            $subject = $request->input('subject');
            $forwardMessage = $request->input('message');

            if (empty($to) || empty($subject) || empty($forwardMessage)) {
                return response()->json([
                    'success' => false,
                    'message' => 'All fields are required'
                ], 422);
            }

            \Log::info("Forwarding email UID {$uid} to {$to}");

            return response()->json([
                'success' => true,
                'message' => 'Email forwarded successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error("Email forward error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to forward email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle star/flag on email
     */
    public function toggleFlag($uid)
    {
        try {
            $client = $this->getClient();
            $folder = $client->getFolder('INBOX');
            $message = $folder->query()->uid($uid)->get()->first();

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found'
                ], 404);
            }

            $flags = $message->getFlags();
            $flagsString = (string) $flags;

            if (str_contains($flagsString, 'Flagged')) {
                $message->unsetFlag('Flagged');
            } else {
                $message->setFlag('Flagged');
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($uid, $attachmentId)
    {
        try {
            $client = $this->getClient();
            $folder = $client->getFolder('INBOX');
            $message = $folder->query()->uid($uid)->get()->first();

            if (!$message) {
                return back()->with('error', 'Message not found');
            }

            $attachments = $message->getAttachments();

            if (isset($attachments[$attachmentId])) {
                $attachment = $attachments[$attachmentId];
                return response()->download(
                    $attachment->getPath(),
                    $attachment->getName(),
                    ['Content-Type' => $attachment->getMimeType()]
                );
            }

            return back()->with('error', 'Attachment not found');

        } catch (\Exception $e) {
            return back()->with('error', 'Error downloading attachment: ' . $e->getMessage());
        }
    }

    /**
     * Save email as draft
     */
    public function saveDraft(Request $request)
    {
        try {
            $host = 'mail.jupitercorporateservices.com';
            $port = 993;
            $username = 'hello@shoreshotelng.com';
            $password = 'hello@shoresEmailLogin';

            $mailbox = "{{$host}:{$port}/imap/ssl/novalidate-cert}Drafts";
            $imap = @imap_open($mailbox, $username, $password);

            if (!$imap) {
                throw new \Exception("Could not connect to IMAP");
            }

            // If updating existing draft, delete the old one first
            if ($request->has('draft_uid')) {
                $oldUid = $request->input('draft_uid');
                imap_delete($imap, $oldUid);
                imap_expunge($imap);
            }

            // Build draft message
            $from = config('mail.from.address', $username);
            $to = $request->input('to', '');
            $subject = $request->input('subject', '(No Subject)');
            $body = $request->input('message', '');
            $date = date('r');

            $boundary = "----=_Part_" . md5(uniqid(time()));

            $parts = [];
            $parts[] = "From: {$from}";
            $parts[] = "To: {$to}";
            $parts[] = "Subject: {$subject}";
            $parts[] = "Date: {$date}";
            $parts[] = "MIME-Version: 1.0";
            $parts[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
            $parts[] = "";
            $parts[] = "--{$boundary}";
            $parts[] = "Content-Type: text/html; charset=UTF-8";
            $parts[] = "";
            $parts[] = $body;
            $parts[] = "";
            $parts[] = "--{$boundary}--";

            $message = implode("\r\n", $parts);

            // Append as draft (no \Seen flag)
            $result = imap_append($imap, $mailbox, $message, "\\Draft");

            if (!$result) {
                throw new \Exception("Failed to save draft");
            }

            // Get the UID of the newly created draft
            $status = imap_status($imap, $mailbox, SA_UIDNEXT);
            $newUid = $status->uidnext - 1;

            imap_close($imap);

            return response()->json([
                'success' => true,
                'draft_uid' => $newUid,
                'message' => 'Draft saved'
            ]);

        } catch (\Exception $e) {
            \Log::error("Draft save error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft'
            ], 500);
        }
    }

    /**
     * Load a draft email
     */
    public function loadDraft($uid)
    {
        try {
            $client = $this->getClient();
            $draftFolderName = $this->getImapFolderName('DRAFT', $client);
            $folder = $client->getFolder($draftFolderName);

            $message = $folder->query()->uid($uid)->get()->first();

            if (!$message) {
                return response()->json(['success' => false, 'message' => 'Draft not found'], 404);
            }

            // Get draft details
            $to = $message->getTo();
            $cc = $message->getCc();
            $bcc = $message->getBcc();

            $draft = [
                'to' => !empty($to) && isset($to[0]) ? $to[0]->mail : '',
                'subject' => $message->getSubject() ? $message->getSubject()->toString() : '',
                'cc' => !empty($cc) && isset($cc[0]) ? $cc[0]->mail : '',
                'bcc' => !empty($bcc) && isset($bcc[0]) ? $bcc[0]->mail : '',
                'message' => $message->hasHTMLBody() ? $message->getHTMLBody() : nl2br($message->getTextBody()),
            ];

            return response()->json([
                'success' => true,
                'draft' => $draft
            ]);

        } catch (\Exception $e) {
            \Log::error("Load draft error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load draft'], 500);
        }
    }

    /**
     * Delete draft
     */
    public function deleteDraft($uid)
    {
        try {
            $client = $this->getClient();
            $draftFolderName = $this->getImapFolderName('DRAFT', $client);
            $folder = $client->getFolder($draftFolderName);

            $message = $folder->query()->uid($uid)->get()->first();

            if ($message) {
                $message->delete();
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error("Draft delete error: " . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Move email to spam
     */
    public function markAsSpam($uid)
    {
        try {
            $client = $this->getClient();
            $inbox = $client->getFolder('INBOX');
            $message = $inbox->query()->uid($uid)->get()->first();

            if ($message) {
                $spamFolder = $this->getImapFolderName('SPAM', $client);
                $message->move($spamFolder);
            }

            return response()->json(['success' => true, 'message' => 'Moved to spam']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark as not spam (move to inbox)
     */
    public function notSpam($uid)
    {
        try {
            $client = $this->getClient();
            $spamFolderName = $this->getImapFolderName('SPAM', $client);
            $spamFolder = $client->getFolder($spamFolderName);
            $message = $spamFolder->query()->uid($uid)->get()->first();

            if ($message) {
                $message->move('INBOX');
            }

            return response()->json(['success' => true, 'message' => 'Moved to inbox']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
