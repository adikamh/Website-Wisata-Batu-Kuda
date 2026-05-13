<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\AdminActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Throwable;

trait RecordsAdminActivity
{
    protected function recordAdminActivity(string $action, string $description, ?Model $subject = null, array $overrides = []): void
    {
        $admin = Auth::user();

        if (! $admin || $admin->role !== 'admin') {
            return;
        }

        try {
            if (! Schema::hasTable('admin_activities')) {
                return;
            }

            $style = $this->adminActivityStyle($action);

            AdminActivity::create([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'action' => $action,
                'subject_type' => $subject ? $subject::class : null,
                'subject_id' => $subject?->getKey(),
                'title' => $overrides['title'] ?? $admin->name,
                'description' => $description,
                'icon' => $overrides['icon'] ?? $style['icon'],
                'icon_bg' => $overrides['icon_bg'] ?? $style['icon_bg'],
                'icon_text' => $overrides['icon_text'] ?? $style['icon_text'],
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function adminActivityStyle(string $action): array
    {
        return match (true) {
            str_contains($action, 'deleted') => [
                'icon' => 'fa-trash-alt',
                'icon_bg' => 'bg-red-100',
                'icon_text' => 'text-red-600',
            ],
            str_contains($action, 'updated') => [
                'icon' => 'fa-pen',
                'icon_bg' => 'bg-amber-100',
                'icon_text' => 'text-amber-600',
            ],
            str_contains($action, 'report') => [
                'icon' => 'fa-file-download',
                'icon_bg' => 'bg-purple-100',
                'icon_text' => 'text-purple-600',
            ],
            str_contains($action, 'gallery') => [
                'icon' => 'fa-images',
                'icon_bg' => 'bg-sky-100',
                'icon_text' => 'text-sky-600',
            ],
            str_contains($action, 'info') => [
                'icon' => 'fa-info-circle',
                'icon_bg' => 'bg-cyan-100',
                'icon_text' => 'text-cyan-600',
            ],
            str_contains($action, 'facility') => [
                'icon' => 'fa-campground',
                'icon_bg' => 'bg-emerald-100',
                'icon_text' => 'text-emerald-600',
            ],
            str_contains($action, 'user') => [
                'icon' => 'fa-user-shield',
                'icon_bg' => 'bg-green-100',
                'icon_text' => 'text-green-600',
            ],
            default => [
                'icon' => 'fa-ticket-alt',
                'icon_bg' => 'bg-indigo-100',
                'icon_text' => 'text-indigo-600',
            ],
        };
    }
}
