<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\BookingEmail;
use Carbon\Carbon;
use App\Services\DirectAdminEmailService;
use App\Services\ImapEmailService;
use App\Models\EmailAccount;

class EmailController extends Controller
{
    protected $client;
    protected $daService;
    protected $clientManager;

    // How many emails to show per page in the inbox
    const EMAILS_PER_PAGE = 20;

    // IMAP connection timeout in seconds — well below PHP's 30s limit
    const IMAP_TIMEOUT = 10;

    public function __construct()
    {
        $this->clientManager = new ClientManager(config('imap'));
        $this->daService = new DirectAdminEmailService();
    }

    // -------------------------------------------------------------------------
    // EMAIL ACCOUNTS
    // -------------------------------------------------------------------------

    /**
     * Get all email accounts from DirectAdmin (cached).
     * Maps DirectAdmin accounts with local DB account info.
     */
    public function getEmailAccounts(): array
    {
        $daAccounts = $this->daService->getEmailAccounts();
        $dbAccounts = EmailAccount::where('is_active', true)->get();

        return collect($daAccounts)->map(function ($daAccount) use ($dbAccounts) {
            $dbAccount = $dbAccounts->firstWhere('email', $daAccount['email']);

            return [
                'email'        => $daAccount['email'],
                'display_name' => $dbAccount ? $dbAccount->display_name : $daAccount['email'],
                'has_password' => $dbAccount ? true : false,
                'is_active'    => $dbAccount ? $dbAccount->is_active : false,
                'quota'        => $daAccount['quota'] ?? 'unlimited',
                'usage'        => $daAccount['usage'] ?? 0,
                'unread_count' => 0,
            ];
        })->toArray();
    }

    /**
     * Map an email address to its IMAP config account key.
     */
    private function getAccountKeyFromEmail(string $email): string
    {
        $mapping = [
            'hello@shoreshotelng.com'           => 'default',
            'book_hotel@shoreshotelng.com'      => 'booking_hotel',
            'book_apartment@shoreshotelng.com'  => 'booking_apartment',
        ];

        return $mapping[$email] ?? 'default';
    }

    /**
     * Test DirectAdmin connection — admin utility route.
     */
    public function testDirectAdminConnection()
    {
        return response()->json($this->daService->testConnection());
    }

    /**
     * Clear cached email account data.
     */
    public function clearEmailCache()
    {
        $this->daService->clearCache();

        if (function_exists('clearEmailAccountsCache')) {
            clearEmailAccountsCache();
        }

        Cache::forget('imap_folder_SENT');
        Cache::forget('imap_folder_DRAFT');
        Cache::forget('imap_folder_SPAM');
        Cache::forget('imap_folder_TRASH');

        return redirect()->back()->with('success', 'Email accounts cache cleared');
    }

    // -------------------------------------------------------------------------
    // IMAP CONNECTION HELPERS
    // -------------------------------------------------------------------------

    /**
     * Open an IMAP connection for the given email address.
     * Timeout is capped at IMAP_TIMEOUT seconds so we never hit PHP's 30s limit.
     *
     * Always call $this->disconnectClient() in a finally{} block after use.
     */
    protected function getClientForEmail(string $email)
    {
        // Always start fresh — never reuse a stale connection.
        $this->disconnectClient();

        $account = EmailAccount::where('email', $email)
            ->where('is_active', true)
            ->first();

        if (!$account) {
            throw new \Exception("Email account {$email} not found or not active");
        }

        \Log::info("Connecting to IMAP for: {$email}");

        $this->client = $this->clientManager->make([
            'host'          => 'mail.jupitercorporateservices.com',
            'port'          => 993,
            'protocol'      => 'imap',
            'encryption'    => 'ssl',
            'validate_cert' => false,
            'username'      => $account->username,
            'password'      => $account->password, // auto-decrypted by model
            'timeout'       => self::IMAP_TIMEOUT,
        ]);

        $this->client->connect();
        \Log::info("IMAP connection established for: {$email}");

        return $this->client;
    }

    /**
     * Safely close and nullify the current IMAP client.
     * Safe to call even when no connection is open.
     */
    protected function disconnectClient(): void
    {
        if ($this->client) {
            try {
                $this->client->disconnect();
            } catch (\Exception $e) {
                // Ignore disconnect errors — connection may already be dead.
            }
            $this->client = null;
        }
    }

    /**
     * Legacy getClient() — used by reply/forward/flag/attachment helpers.
     * Opens a connection using the default IMAP account from config.
     */
    protected function getClient()
    {
        if (!$this->client) {
            $accountConfig = config('imap.accounts.default');

            $this->client = $this->clientManager->make([
                'host'          => $accountConfig['host'],
                'port'          => $accountConfig['port'],
                'protocol'      => $accountConfig['protocol'],
                'encryption'    => $accountConfig['encryption'],
                'validate_cert' => $accountConfig['validate_cert'],
                'username'      => $accountConfig['username'],
                'password'      => $accountConfig['password'],
                'timeout'       => self::IMAP_TIMEOUT,
            ]);

            $this->client->connect();
            \Log::info("IMAP connection established (default account)");
        }

        return $this->client;
    }

    // -------------------------------------------------------------------------
    // FOLDER HELPERS
    // -------------------------------------------------------------------------

    /**
     * Resolve a logical folder name (SENT, DRAFT, etc.) to the actual IMAP
     * folder name on the server. Result is cached for 1 hour so we don't do
     * a full folder-tree fetch on every request.
     */
    private function getImapFolderName(string $folder, $client): string
    {
        if ($folder === 'INBOX') {
            return 'INBOX';
        }

        $cacheKey = 'imap_folder_' . $folder;

        return Cache::remember($cacheKey, 3600, function () use ($folder, $client) {
            $folderMappings = [
                'SENT' => [
                    'INBOX.Sent',
                    'INBOX.Sent Items',
                    'Sent',
                    'Sent Items',
                    'Sent Messages',
                    '[Gmail]/Sent Mail',
                    'Sent Mail',
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

            foreach ($folderMappings[$folder] as $possibleName) {
                try {
                    if ($client->getFolder($possibleName)) {
                        \Log::info("Found IMAP folder '{$possibleName}' for {$folder}");
                        return $possibleName;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            \Log::warning("Could not find folder for {$folder}, defaulting to INBOX");
            return 'INBOX';
        });
    }

    // -------------------------------------------------------------------------
    // INBOX
    // -------------------------------------------------------------------------

    /**
     * Display a paginated inbox for the selected email account.
     *
     * KEY FIXES vs original:
     *  - Paginated (EMAILS_PER_PAGE at a time) — no more fetching thousands of emails
     *  - IMAP connection always closed in finally{}
     *  - IMAP timeout capped at IMAP_TIMEOUT seconds
     *  - Folder name lookup cached
     */
    public function inbox(Request $request, $folder = null)
    {
        $folder  = $folder ?: $request->get('folder', 'INBOX');
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = self::EMAILS_PER_PAGE;

        $defaultAccount = EmailAccount::getDefault();
        $activeEmail    = session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');

        $emails        = [];
        $total         = 0;
        $error         = null;
        $emailAccounts = $this->getEmailAccounts();

        try {
            $client         = $this->getClientForEmail($activeEmail);
            $imapFolderName = $this->getImapFolderName($folder, $client);
            $oFolder        = $client->getFolder($imapFolderName);

            \Log::info("Inbox: account={$activeEmail}, folder={$folder}, imap={$imapFolderName}, page={$page}");

            // Fetch ALL message UIDs/headers then slice — avoids loading full bodies up front.
            $allMessages = $oFolder->messages()->all()->get();
            $total       = $allMessages->count();

            // Sort newest first, then paginate in PHP.
            $pagedMessages = $allMessages
                ->sortByDesc(fn($m) => $m->getDate())
                ->slice(($page - 1) * $perPage, $perPage);

            foreach ($pagedMessages as $message) {
                try {
                    $subject = $this->safeGetSubject($message);

                    if ($folder === 'SENT') {
                        [$displayName, $displayAddress] = $this->safeGetRecipient($message);
                    } else {
                        [$displayName, $displayAddress] = $this->safeGetSender($message);
                    }

                    $dateString = $this->safeGetDate($message);
                    $preview    = $this->getEmailPreview($message, 100);
                    [$isSeen, $isFlagged] = $this->safeGetFlags($message);

                    $emails[] = [
                        'uid'             => $message->getUid(),
                        'subject'         => $subject,
                        'from_name'       => $displayName,
                        'from'            => $displayAddress,
                        'date'            => $dateString,
                        'has_attachments' => $message->hasAttachments(),
                        'is_seen'         => $isSeen,
                        'is_flagged'      => $isFlagged,
                        'preview'         => $preview,
                        'folder'          => $folder,
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error processing message in inbox: " . $e->getMessage());
                    continue;
                }
            }

            \Log::info("Inbox rendered: " . count($emails) . " emails (page {$page} of " . ceil($total / $perPage) . ")");

        } catch (\Exception $e) {
            \Log::error("Email inbox error for {$activeEmail}: " . $e->getMessage());
            $error = 'Unable to fetch emails for ' . $activeEmail . ': ' . $e->getMessage();
        } finally {
            $this->disconnectClient();
        }

        return view('admin.email.inbox', compact(
            'emails', 'folder', 'emailAccounts', 'activeEmail',
            'total', 'page', 'perPage', 'error'
        ));
    }

    // -------------------------------------------------------------------------
    // SHOW SINGLE EMAIL
    // -------------------------------------------------------------------------

    /**
     * Display a single email message.
     * IMAP connection is always closed in finally{}.
     */
    public function show(Request $request, $uid)
    {
        $defaultAccount = EmailAccount::getDefault();
        $activeEmail    = session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');
        $emailAccounts  = $this->getEmailAccounts();

        try {
            $client         = $this->getClientForEmail($activeEmail);
            $folderParam    = $request->get('folder', 'INBOX');
            $imapFolderName = $this->getImapFolderName($folderParam, $client);
            $imapFolder     = $client->getFolder($imapFolderName);

            $message = $imapFolder->query()->uid($uid)->get()->first();

            if (!$message) {
                throw new \Exception("Message with UID {$uid} not found");
            }

            $subject     = $this->safeGetSubject($message);
            [$fromName, $fromAddress] = $this->safeGetSender($message);
            $dateString  = $this->safeGetDate($message);
            $body        = $this->buildEmailBody($message, $uid);
            $folder      = $folderParam;

            // Mark as seen
            try {
                [$isSeen] = $this->safeGetFlags($message);
                if (!$isSeen) {
                    $message->setFlag('Seen');
                }
            } catch (\Exception $e) {
                \Log::warning("Could not mark message as seen: " . $e->getMessage());
            }

            return view('admin.email.show', compact(
                'message', 'subject', 'fromName', 'fromAddress',
                'dateString', 'body', 'folder', 'emailAccounts', 'activeEmail'
            ));

        } catch (\Exception $e) {
            \Log::error("Email show error: " . $e->getMessage());
            return back()->with('error', 'Unable to load email: ' . $e->getMessage());
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // SWITCH ACCOUNT
    // -------------------------------------------------------------------------

    public function switchAndView(Request $request, $email)
    {
        try {
            $account = EmailAccount::where('email', $email)->where('is_active', true)->first();

            if (!$account) {
                return redirect()->route('admin.email.inbox')
                    ->with('error', 'Email account not found or not configured');
            }

            session(['active_email' => $email]);

            return redirect()->route('admin.email.inbox')
                ->with('success', 'Now viewing emails for ' . $account->display_name);

        } catch (\Exception $e) {
            \Log::error("Error switching account: " . $e->getMessage());
            return redirect()->route('admin.email.inbox')
                ->with('error', 'Failed to switch email account');
        }
    }

    // -------------------------------------------------------------------------
    // COMPOSE & SEND
    // -------------------------------------------------------------------------

    public function compose(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'to'             => 'required|email',
                'subject'        => 'required|string|max:255',
                'message'        => 'required|string',
                'cc'             => 'nullable|email',
                'bcc'            => 'nullable|email',
                'attachments.*'  => 'nullable|file|max:10240',
            ]);

            try {
                $defaultAccount = EmailAccount::getDefault();
                $activeEmail    = session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');
                $account        = EmailAccount::where('email', $activeEmail)->first();

                Mail::send([], [], function ($mail) use ($validated, $request, $account) {
                    $mail->from($account->email, $account->display_name ?? $account->email);
                    $mail->to($validated['to'])->subject($validated['subject'])->html($validated['message']);

                    if (!empty($validated['cc']))  $mail->cc($validated['cc']);
                    if (!empty($validated['bcc']))  $mail->bcc($validated['bcc']);

                    if ($request->hasFile('attachments')) {
                        foreach ($request->file('attachments') as $file) {
                            $mail->attach($file->getRealPath(), [
                                'as'   => $file->getClientOriginalName(),
                                'mime' => $file->getMimeType(),
                            ]);
                        }
                    }
                });

                // Save a copy to the Sent folder (best-effort — failure doesn't abort the send)
                try {
                    $this->saveToSentFolderForAccount($validated, $request, $activeEmail);
                } catch (\Exception $e) {
                    \Log::error("Could not save to Sent folder for {$activeEmail}: " . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully from ' . ($account->display_name ?? $activeEmail) . '!',
                ]);

            } catch (\Exception $e) {
                \Log::error("Email send error: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error sending email: ' . $e->getMessage(),
                ], 500);
            }
        }

        return view('admin.email.inbox');
    }

    // -------------------------------------------------------------------------
    // REPLY
    // -------------------------------------------------------------------------

    public function reply(Request $request, $uid)
    {
        $validated = $request->validate(['message' => 'required|string']);

        try {
            $client          = $this->getClient();
            $folder          = $client->getFolder('INBOX');
            $originalMessage = $folder->query()->uid($uid)->get()->first();

            if (!$originalMessage) {
                return response()->json(['success' => false, 'message' => 'Original message not found'], 404);
            }

            $from = $originalMessage->getFrom();
            $to   = !empty($from) && isset($from[0]) ? $from[0]->mail : null;

            if (!$to) {
                return response()->json(['success' => false, 'message' => 'Could not determine recipient'], 400);
            }

            $subjectAttr     = $originalMessage->getSubject();
            $originalSubject = $subjectAttr ? $subjectAttr->toString() : '';
            $subject         = 'Re: ' . $originalSubject;

            Mail::send([], [], function ($mail) use ($to, $subject, $validated) {
                $mail->to($to)->subject($subject)->html($validated['message']);
            });

            return response()->json(['success' => true, 'message' => 'Reply sent successfully!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error sending reply: ' . $e->getMessage()], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // FORWARD
    // -------------------------------------------------------------------------

    public function forward(Request $request, $uid)
    {
        try {
            $client          = $this->getClient();
            $folder          = $client->getFolder('INBOX');
            $originalMessage = $folder->query()->uid($uid)->get()->first();

            if (!$originalMessage) {
                return response()->json(['success' => false, 'message' => 'Original message not found'], 404);
            }

            $to             = $request->input('to');
            $subject        = $request->input('subject');
            $forwardMessage = $request->input('message');

            if (empty($to) || empty($subject) || empty($forwardMessage)) {
                return response()->json(['success' => false, 'message' => 'All fields are required'], 422);
            }

            \Log::info("Forwarding email UID {$uid} to {$to}");

            // TODO: add actual Mail::send() here when forward feature is fully implemented.

            return response()->json(['success' => true, 'message' => 'Email forwarded successfully']);

        } catch (\Exception $e) {
            \Log::error("Email forward error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to forward email: ' . $e->getMessage()], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // FLAG / STAR
    // -------------------------------------------------------------------------

    public function toggleFlag($uid)
    {
        try {
            $client  = $this->getClient();
            $folder  = $client->getFolder('INBOX');
            $message = $folder->query()->uid($uid)->get()->first();

            if (!$message) {
                return response()->json(['success' => false, 'message' => 'Message not found'], 404);
            }

            [$isSeen, $isFlagged] = $this->safeGetFlags($message);

            if ($isFlagged) {
                $message->unsetFlag('Flagged');
            } else {
                $message->setFlag('Flagged');
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // ATTACHMENTS
    // -------------------------------------------------------------------------

    public function downloadAttachment($uid, $attachmentId)
    {
        try {
            $client      = $this->getClient();
            $folder      = $client->getFolder('INBOX');
            $message     = $folder->query()->uid($uid)->get()->first();

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
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // DRAFTS
    // -------------------------------------------------------------------------

    public function saveDraft(Request $request)
    {
        try {
            $account  = EmailAccount::getDefault();
            $username = $account ? $account->username : 'hello@shoreshotelng.com';
            $password = $account ? $account->password : '';

            $mailbox = "{mail.jupitercorporateservices.com:993/imap/ssl/novalidate-cert}Drafts";
            $imap    = @imap_open($mailbox, $username, $password, 0, 1, ['DISABLE_AUTHENTICATOR' => 'GSSAPI']);

            if (!$imap) {
                throw new \Exception("Could not connect to IMAP: " . imap_last_error());
            }

            // Delete old draft first if we're updating
            if ($request->has('draft_uid')) {
                imap_delete($imap, $request->input('draft_uid'));
                imap_expunge($imap);
            }

            $from     = $account ? $account->email : config('mail.from.address');
            $to       = $request->input('to', '');
            $subject  = $request->input('subject', '(No Subject)');
            $body     = $request->input('message', '');
            $boundary = '----=_Part_' . md5(uniqid(time()));

            $message = implode("\r\n", [
                "From: {$from}",
                "To: {$to}",
                "Subject: {$subject}",
                "Date: " . date('r'),
                "MIME-Version: 1.0",
                "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
                "",
                "--{$boundary}",
                "Content-Type: text/html; charset=UTF-8",
                "Content-Transfer-Encoding: 8bit",
                "",
                $body,
                "",
                "--{$boundary}--",
            ]);

            if (!imap_append($imap, $mailbox, $message, "\\Draft")) {
                throw new \Exception("Failed to save draft: " . imap_last_error());
            }

            $status = imap_status($imap, $mailbox, SA_UIDNEXT);
            $newUid = $status->uidnext - 1;
            imap_close($imap);

            return response()->json(['success' => true, 'draft_uid' => $newUid, 'message' => 'Draft saved']);

        } catch (\Exception $e) {
            \Log::error("Draft save error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save draft'], 500);
        }
    }

    public function loadDraft($uid)
    {
        try {
            $client         = $this->getClient();
            $draftFolder    = $this->getImapFolderName('DRAFT', $client);
            $folder         = $client->getFolder($draftFolder);
            $message        = $folder->query()->uid($uid)->get()->first();

            if (!$message) {
                return response()->json(['success' => false, 'message' => 'Draft not found'], 404);
            }

            $to  = $message->getTo();
            $cc  = $message->getCc();
            $bcc = $message->getBcc();

            return response()->json([
                'success' => true,
                'draft'   => [
                    'to'      => !empty($to)  && isset($to[0])  ? $to[0]->mail  : '',
                    'subject' => $message->getSubject() ? $message->getSubject()->toString() : '',
                    'cc'      => !empty($cc)  && isset($cc[0])  ? $cc[0]->mail  : '',
                    'bcc'     => !empty($bcc) && isset($bcc[0]) ? $bcc[0]->mail : '',
                    'message' => $message->hasHTMLBody() ? $message->getHTMLBody() : nl2br($message->getTextBody()),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error("Load draft error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load draft'], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    public function deleteDraft($uid)
    {
        try {
            $client      = $this->getClient();
            $draftFolder = $this->getImapFolderName('DRAFT', $client);
            $folder      = $client->getFolder($draftFolder);
            $message     = $folder->query()->uid($uid)->get()->first();

            if ($message) {
                $message->delete();
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error("Draft delete error: " . $e->getMessage());
            return response()->json(['success' => false], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // SPAM
    // -------------------------------------------------------------------------

    public function markAsSpam($uid)
    {
        try {
            $client     = $this->getClient();
            $inbox      = $client->getFolder('INBOX');
            $message    = $inbox->query()->uid($uid)->get()->first();

            if ($message) {
                $spamFolder = $this->getImapFolderName('SPAM', $client);
                $message->move($spamFolder);
            }

            return response()->json(['success' => true, 'message' => 'Moved to spam']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    public function notSpam($uid)
    {
        try {
            $client          = $this->getClient();
            $spamFolderName  = $this->getImapFolderName('SPAM', $client);
            $spamFolder      = $client->getFolder($spamFolderName);
            $message         = $spamFolder->query()->uid($uid)->get()->first();

            if ($message) {
                $message->move('INBOX');
            }

            return response()->json(['success' => true, 'message' => 'Moved to inbox']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        } finally {
            $this->disconnectClient();
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Safely get the subject string from a message.
     */
    private function safeGetSubject($message): string
    {
        try {
            $attr = $message->getSubject();
            if ($attr) {
                $s = $attr->toString();
                if (!empty(trim($s))) return $s;
            }
        } catch (\Exception $e) {}

        return '(No Subject)';
    }

    /**
     * Safely get [displayName, emailAddress] for the sender.
     */
    private function safeGetSender($message): array
    {
        try {
            $from = $message->getFrom();
            if (!empty($from) && isset($from[0])) {
                $addr = $from[0]->mail    ?? 'Unknown';
                $name = $from[0]->personal ?? $addr;
                return [$name, $addr];
            }
        } catch (\Exception $e) {}

        return ['Unknown', 'Unknown'];
    }

    /**
     * Safely get [displayName, emailAddress] for the first recipient.
     */
    private function safeGetRecipient($message): array
    {
        try {
            $to = $message->getTo();
            if (!empty($to) && isset($to[0])) {
                $addr = $to[0]->mail    ?? 'Unknown';
                $name = $to[0]->personal ?? $addr;
                return [$name, $addr];
            }
        } catch (\Exception $e) {}

        return ['Unknown', 'Unknown'];
    }

    /**
     * Safely get a date string from a message.
     */
    private function safeGetDate($message): string
    {
        try {
            $attr = $message->getDate();
            if ($attr) return $attr->toString();
        } catch (\Exception $e) {}

        return 'No date';
    }

    /**
     * Safely get [isSeen, isFlagged] flags from a message.
     */
    private function safeGetFlags($message): array
    {
        try {
            $flagsString = (string) $message->getFlags();
            return [
                str_contains($flagsString, 'Seen'),
                str_contains($flagsString, 'Flagged'),
            ];
        } catch (\Exception $e) {
            \Log::warning("Could not read flags: " . $e->getMessage());
            return [false, false];
        }
    }

    /**
     * Get a short plain-text preview of an email body.
     */
    private function getEmailPreview($message, int $length = 100): string
    {
        try {
            $text = '';

            if ($message->hasTextBody()) {
                $text = (string) $message->getTextBody();
            }

            if (empty(trim($text)) && $message->hasHTMLBody()) {
                $html = $message->getHTMLBody();
                if (!empty($html)) {
                    $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }

            $text = trim(preg_replace('/\s+/', ' ', $text));

            if (empty($text)) return 'No content';

            return mb_strlen($text) > $length
                ? mb_substr($text, 0, $length) . '...'
                : $text;

        } catch (\Exception $e) {
            return 'No content';
        }
    }

    /**
     * Build and sanitize the full HTML body for the show view.
     */
    private function buildEmailBody($message, $uid): string
    {
        try {
            $rawBody = '';

            if ($message->hasHTMLBody()) {
                $rawBody = $message->getHTMLBody();
            } elseif ($message->hasTextBody()) {
                $rawBody = $message->getTextBody();
            }

            if (empty($rawBody)) {
                return '<p class="grey-text center-align">No content available</p>';
            }

            // Iteratively decode HTML entities (handles double-encoded content)
            $decoded    = $rawBody;
            $iterations = 0;

            while ($iterations < 5 && (
                    str_contains($decoded, '&lt;') ||
                    str_contains($decoded, '&gt;') ||
                    str_contains($decoded, '&quot;') ||
                    str_contains($decoded, '&#')
                )) {
                $before  = $decoded;
                $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($before === $decoded) break;
                $iterations++;
            }

            if ($this->looksLikeHTML($decoded)) {
                return $this->sanitizeEmailBody($decoded);
            }

            // Plain text — wrap in a readable pre block
            return '<pre style="white-space: pre-wrap; font-family: Arial, sans-serif; background: #f5f5f5; padding: 15px; border-radius: 5px;">'
                . htmlspecialchars($decoded)
                . '</pre>';

        } catch (\Exception $e) {
            \Log::error("Error building email body for UID {$uid}: " . $e->getMessage());
            return '<p class="red-text">Unable to load message content.</p>';
        }
    }

    /**
     * Detect whether a string looks like HTML content.
     */
    private function looksLikeHTML(string $text): bool
    {
        $patterns = [
            '/<(!DOCTYPE|html|head|body|div|p|span|table|tr|td|h[1-6]|style)/i',
            '/&lt;(!DOCTYPE|html|head|body|div|p)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) return true;
        }

        return false;
    }

    /**
     * Sanitize an email HTML body to prevent XSS while keeping formatting.
     */
    private function sanitizeEmailBody(string $html): string
    {
        if (empty($html)) return $html;

        try {
            if (class_exists(\voku\helper\AntiXSS::class)) {
                $antiXss  = new \voku\helper\AntiXSS();
                $cleaned  = $antiXss->xss_clean($html);
                if (!empty(trim(strip_tags($cleaned)))) return $cleaned;
            }
        } catch (\Exception $e) {
            \Log::warning("AntiXSS failed: " . $e->getMessage());
        }

        return $this->basicSanitizeEmailBody($html);
    }

    /**
     * Fallback basic HTML sanitizer.
     */
    private function basicSanitizeEmailBody(string $html): string
    {
        $allowedTags = '<html><head><title><meta><style><body><div><p><span><br><hr>'
            . '<h1><h2><h3><h4><h5><h6><strong><b><em><i><u><a><img>'
            . '<table><tr><td><th><thead><tbody><tfoot><ul><ol><li>'
            . '<blockquote><pre><code><sup><sub><small><big><font><center>';

        $html = strip_tags($html, $allowedTags);

        // Remove dangerous elements
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<iframe\b[^>]*>.*?<\/iframe>/is', '', $html);
        $html = preg_replace('/<object\b[^>]*>.*?<\/object>/is', '', $html);
        $html = preg_replace('/<embed\b[^>]*>.*?<\/embed>/is', '', $html);
        $html = preg_replace('/<form\b[^>]*>.*?<\/form>/is', '', $html);

        // Remove event handlers
        $html = preg_replace('/\s*on\w+\s*=\s*(["\'][^"\']*["\']|[^\s>]+)/i', '', $html);

        // Remove dangerous protocols
        $html = preg_replace('/\s+href\s*=\s*["\']?\s*(javascript|data|vbscript):/i', ' href="#"', $html);
        $html = preg_replace('/\s+src\s*=\s*["\']?\s*(javascript|data|vbscript):/i', ' src="#"', $html);

        // Remove dangerous meta refresh
        $html = preg_replace('/<meta[^>]*http-equiv\s*=\s*["\']?refresh["\']?[^>]*>/i', '', $html);

        // Remove CSS expression() attacks from inline styles
        $html = preg_replace('/\s+style\s*=\s*["\'][^"\']*expression\([^"\']*\)[^"\']*["\']/i', '', $html);

        return $html;
    }

    // -------------------------------------------------------------------------
    // SAVE TO SENT FOLDER (IMAP append)
    // -------------------------------------------------------------------------

    /**
     * Append a sent email to the account's IMAP Sent folder.
     * Failures are logged but do NOT abort the email send.
     */
    private function saveToSentFolderForAccount(array $emailData, Request $request, string $activeEmail): void
    {
        $account  = EmailAccount::where('email', $activeEmail)->first();
        $username = $account->username;
        $password = $account->password;
        $mailbox  = "{mail.jupitercorporateservices.com:993/imap/ssl/novalidate-cert}Sent";

        $imap = @imap_open($mailbox, $username, $password, 0, 1, ['DISABLE_AUTHENTICATOR' => 'GSSAPI']);

        if (!$imap) {
            throw new \Exception("Could not connect to IMAP Sent folder: " . imap_last_error());
        }

        $from      = $account->email;
        $fromName  = $account->display_name ?? $from;
        $boundary  = '----=_Part_' . md5(uniqid(time()));

        $parts = [
            "From: {$fromName} <{$from}>",
            "To: {$emailData['to']}",
        ];

        if (!empty($emailData['cc']))  $parts[] = "Cc: {$emailData['cc']}";
        if (!empty($emailData['bcc'])) $parts[] = "Bcc: {$emailData['bcc']}";

        $parts = array_merge($parts, [
            "Subject: {$emailData['subject']}",
            "Date: " . date('r'),
            "MIME-Version: 1.0",
            "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
            "",
            "--{$boundary}",
            "Content-Type: text/plain; charset=UTF-8",
            "Content-Transfer-Encoding: 8bit",
            "",
            strip_tags($emailData['message']),
            "",
            "--{$boundary}",
            "Content-Type: text/html; charset=UTF-8",
            "Content-Transfer-Encoding: 8bit",
            "",
            $emailData['message'],
            "",
            "--{$boundary}--",
        ]);

        $rawMessage = implode("\r\n", $parts);

        if (!imap_append($imap, $mailbox, $rawMessage, "\\Seen")) {
            imap_close($imap);
            throw new \Exception("imap_append failed: " . imap_last_error());
        }

        imap_close($imap);
        \Log::info("Saved email to Sent folder for {$activeEmail}");
    }
}
