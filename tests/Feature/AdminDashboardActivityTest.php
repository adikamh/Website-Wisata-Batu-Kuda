<?php

namespace Tests\Feature;

use App\Models\AdminActivity;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_uses_admin_activity_log_only(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Batu Kuda',
            'username' => 'admin_batu_kuda',
            'role' => 'admin',
            'is_verified' => true,
        ]);

        $customer = User::factory()->create([
            'name' => 'Pengunjung Test',
            'username' => 'pengunjung_test',
            'role' => 'user',
            'is_verified' => true,
        ]);

        Transaction::create([
            'user_id' => $customer->id,
            'total_bayar' => 75000,
            'status_pembayaran' => 'success',
            'payment_method' => 'test',
        ]);

        AdminActivity::create([
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_email' => $admin->email,
            'action' => 'ticket_updated',
            'title' => $admin->name,
            'description' => 'memperbarui tiket "Camping"',
            'icon' => 'fa-pen',
            'icon_bg' => 'bg-amber-100',
            'icon_text' => 'text-amber-600',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('recentActivities', function ($activities) use ($admin, $customer) {
            $activityText = $activities
                ->map(fn (array $activity) => $activity['title'] . ' ' . $activity['description'])
                ->implode(' ');

            return $activities->count() === 1
                && $activities->first()['title'] === $admin->name
                && $activities->first()['description'] === 'memperbarui tiket "Camping"'
                && ! str_contains($activityText, $customer->name)
                && ! str_contains($activityText, 'Pembelian tiket')
                && ! str_contains($activityText, 'Pengguna baru terdaftar');
        });
    }
}
