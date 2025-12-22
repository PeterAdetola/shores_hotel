<?php

use App\Services\DirectAdminEmailService;
use App\Models\EmailAccount;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getEmailAccountsForSidebar')) {
    /**
     * Get email accounts with DirectAdmin and database info for sidebar display
     * Cached for 5 minutes to avoid repeated API calls
     *
     * @return array
     */
    function getEmailAccountsForSidebar()
    {
        return Cache::remember('sidebar_email_accounts', 300, function () {
            try {
                $daService = new DirectAdminEmailService();

                // Get DirectAdmin accounts
                $daAccounts = $daService->getEmailAccounts();

                // Get database accounts
                $dbAccounts = EmailAccount::where('is_active', true)->get();

                // Map them together
                $emailAccounts = collect($daAccounts)->map(function ($daAccount) use ($dbAccounts) {
                    $dbAccount = $dbAccounts->firstWhere('email', $daAccount['email']);

                    return [
                        'email' => $daAccount['email'],
                        'display_name' => $dbAccount ? $dbAccount->display_name : $daAccount['email'],
                        'has_password' => $dbAccount ? true : false,
                        'is_active' => $dbAccount ? $dbAccount->is_active : false,
                        'quota' => $daAccount['quota'] ?? 'unlimited',
                        'usage' => $daAccount['usage'] ?? 0,
                        'unread_count' => 0, // Can be enhanced later with AJAX
                    ];
                })->toArray();

                return $emailAccounts;

            } catch (\Exception $e) {
                \Log::error("Failed to get email accounts for sidebar: " . $e->getMessage());
                return [];
            }
        });
    }
}

if (!function_exists('getActiveEmailAccount')) {
    /**
     * Get the currently active email account from session or default
     *
     * @return string
     */
    function getActiveEmailAccount()
    {
        $defaultAccount = EmailAccount::getDefault();
        return session('active_email', $defaultAccount ? $defaultAccount->email : 'hello@shoreshotelng.com');
    }
}

if (!function_exists('clearEmailAccountsCache')) {
    /**
     * Clear the cached email accounts for sidebar
     * Call this when email accounts are added/removed/updated
     *
     * @return void
     */
    function clearEmailAccountsCache()
    {
        Cache::forget('sidebar_email_accounts');
        \Log::info('Sidebar email accounts cache cleared');
    }
}
