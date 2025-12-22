<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\DirectAdminEmailService;
use App\Models\EmailAccount;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer([
            'admin.email.*',           // All email views
            'admin.components.*', // Sidebar component
            'admin'            // Admin layout (if it exists)
        ], function ($view) {
            \Log::info('ViewComposer triggered for: ' . $view->name());
            try {
                // Get accounts from DirectAdmin
                $daService = new DirectAdminEmailService();
                $daAccounts = $daService->getEmailAccounts();

                // Get accounts from database
                $dbAccounts = EmailAccount::where('is_active', true)->get();

                // Merge both sources
                $emailAccounts = collect($daAccounts)->map(function ($daAccount) use ($dbAccounts) {
                    $dbAccount = $dbAccounts->firstWhere('email', $daAccount['email']);

                    return [
                        'email' => $daAccount['email'],
                        'display_name' => $dbAccount->display_name ?? $daAccount['email'],
                        'has_password' => $dbAccount ? true : false,
                        'is_active' => $dbAccount ? $dbAccount->is_active : false,
                    ];
                })->toArray();

                // Get active email from session
                $defaultAccount = EmailAccount::where('is_default', true)
                    ->where('is_active', true)
                    ->first();

                $activeEmail = session('active_email', $defaultAccount ? $defaultAccount->email : null);

                // Share with views
                $view->with('emailAccounts', $emailAccounts);
                $view->with('activeEmail', $activeEmail);

            } catch (\Exception $e) {
                \Log::error('Error loading email accounts: ' . $e->getMessage());
                $view->with('emailAccounts', []);
                $view->with('activeEmail', null);
            }
        });
    }
}
