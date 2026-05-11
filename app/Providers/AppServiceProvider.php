<?php

namespace App\Providers;

use App\Models\Transaction;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'local') {
            URL::forceScheme('https');
        }

        View::composer('layout.admin-panel', function ($view) {
            try {
                $notifications = Transaction::query()
                    ->with(['user', 'details'])
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(fn (Transaction $transaction) => [
                        'buyer' => $transaction->user->name ?? 'Pengguna',
                        'quantity' => max(1, (int) $transaction->details->sum('quantity')),
                        'time' => $transaction->created_at,
                    ]);
            } catch (Throwable) {
                $notifications = collect();
            }

            if ($notifications->isEmpty()) {
                $notifications = collect([
                    [
                        'buyer' => 'Budi',
                        'quantity' => 2,
                        'time' => now()->subMinutes(5),
                    ],
                    [
                        'buyer' => 'Sari',
                        'quantity' => 1,
                        'time' => now()->subMinutes(18),
                    ],
                    [
                        'buyer' => 'Andi',
                        'quantity' => 4,
                        'time' => now()->subHour(),
                    ],
                ]);
            }

            $view->with('adminTicketNotifications', $notifications);
        });
    }
}
