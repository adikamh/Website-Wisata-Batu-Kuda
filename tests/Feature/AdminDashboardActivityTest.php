<?php

namespace Tests\Feature;

use App\Models\AdminActivity;
use App\Models\TiketKategori;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

    public function test_admin_dashboard_statistics_use_successful_transaction_details(): void
    {
        $this->travelTo(Carbon::parse('2026-06-03 10:00:00'));

        $admin = User::factory()->create([
            'name' => 'Admin Statistik',
            'username' => 'admin_statistik',
            'role' => 'admin',
            'is_verified' => true,
        ]);

        $customer = User::factory()->create([
            'name' => 'Pengunjung Statistik',
            'username' => 'pengunjung_statistik',
            'role' => 'user',
            'is_verified' => true,
        ]);

        $wisata = Wisata::create([
            'nama_wisata' => 'Batu Kuda',
            'deskripsi' => 'Wisata alam Batu Kuda.',
            'lokasi' => 'Cimenyan, Bandung',
            'gambar_url' => 'images/hero.jpeg',
        ]);

        $visitTicket = TiketKategori::create([
            'wisata_id' => $wisata->id,
            'nama_kategori' => 'Kunjungan Harian',
            'harga' => 10000,
        ]);

        $campingTicket = TiketKategori::create([
            'wisata_id' => $wisata->id,
            'nama_kategori' => 'Camping',
            'harga' => 50000,
        ]);

        $visitTransaction = Transaction::create([
            'user_id' => $customer->id,
            'total_bayar' => 20000,
            'status_pembayaran' => 'success',
            'payment_method' => 'test',
        ]);

        TransactionDetail::create([
            'transaction_id' => $visitTransaction->id,
            'tiket_kategori_id' => $visitTicket->id,
            'quantity' => 2,
            'subtotal' => 20000,
            'package_type' => 'visit',
            'start_date' => today(),
            'end_date' => today(),
            'total_days' => 1,
            'grand_total' => 20000,
        ]);

        $campingTransaction = Transaction::create([
            'user_id' => $customer->id,
            'total_bayar' => 150000,
            'status_pembayaran' => 'success',
            'payment_method' => 'test',
        ]);

        TransactionDetail::create([
            'transaction_id' => $campingTransaction->id,
            'tiket_kategori_id' => $campingTicket->id,
            'quantity' => 3,
            'subtotal' => 150000,
            'package_type' => 'camping',
            'start_date' => today(),
            'end_date' => today(),
            'total_days' => 1,
            'grand_total' => 150000,
        ]);

        $pendingTransaction = Transaction::create([
            'user_id' => $customer->id,
            'total_bayar' => 999999,
            'status_pembayaran' => 'pending',
            'payment_method' => 'test',
        ]);

        TransactionDetail::create([
            'transaction_id' => $pendingTransaction->id,
            'tiket_kategori_id' => $campingTicket->id,
            'quantity' => 9,
            'subtotal' => 999999,
            'package_type' => 'camping',
            'start_date' => today(),
            'end_date' => today(),
            'total_days' => 1,
            'grand_total' => 999999,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('stats', fn (array $stats) => $stats['today_revenue'] === 170000.0
            && $stats['tickets_sold'] === 5
            && $stats['camping_orders'] === 1);
        $response->assertViewHas('chartData', fn ($chartData) => (float) $chartData->last() === 170000.0);
    }

    public function test_admin_can_approve_pending_transaction_for_dashboard_statistics(): void
    {
        $this->travelTo(Carbon::parse('2026-06-03 10:00:00'));

        $admin = User::factory()->create([
            'name' => 'Admin Approval',
            'username' => 'admin_approval',
            'role' => 'admin',
            'is_verified' => true,
        ]);

        $customer = User::factory()->create([
            'name' => 'Pengunjung Approval',
            'username' => 'pengunjung_approval',
            'role' => 'user',
            'is_verified' => true,
        ]);

        $wisata = Wisata::create([
            'nama_wisata' => 'Batu Kuda',
            'deskripsi' => 'Wisata alam Batu Kuda.',
            'lokasi' => 'Cimenyan, Bandung',
            'gambar_url' => 'images/hero.jpeg',
        ]);

        $ticket = TiketKategori::create([
            'wisata_id' => $wisata->id,
            'nama_kategori' => 'Kunjungan Harian',
            'harga' => 10000,
        ]);

        $transaction = Transaction::create([
            'user_id' => $customer->id,
            'total_bayar' => 40000,
            'status_pembayaran' => 'pending',
            'payment_method' => 'test',
        ]);

        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'tiket_kategori_id' => $ticket->id,
            'quantity' => 4,
            'subtotal' => 40000,
            'package_type' => 'visit',
            'start_date' => today(),
            'end_date' => today(),
            'total_days' => 1,
            'grand_total' => 40000,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.transactions.approve', $transaction))
            ->assertRedirect(route('admin.tickets'));

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status_pembayaran' => 'success',
        ]);

        $dashboard = $this->actingAs($admin)->get(route('admin.dashboard'));

        $dashboard->assertOk();
        $dashboard->assertViewHas('stats', fn (array $stats) => $stats['today_revenue'] === 40000.0
            && $stats['tickets_sold'] === 4);
        $dashboard->assertViewHas('chartData', fn ($chartData) => (float) $chartData->last() === 40000.0);
    }

    public function test_admin_ticket_table_can_search_filter_and_limit_transactions(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Filter',
            'username' => 'admin_filter',
            'role' => 'admin',
            'is_verified' => true,
        ]);

        $matchingCustomer = User::factory()->create([
            'name' => 'Siti Approval',
            'username' => 'siti_approval',
            'role' => 'user',
            'is_verified' => true,
        ]);

        $otherCustomer = User::factory()->create([
            'name' => 'Pengunjung Lain',
            'username' => 'pengunjung_lain',
            'role' => 'user',
            'is_verified' => true,
        ]);

        for ($i = 0; $i < 12; $i++) {
            Transaction::create([
                'user_id' => $otherCustomer->id,
                'total_bayar' => 10000 + $i,
                'status_pembayaran' => 'success',
                'payment_method' => 'test',
            ]);
        }

        $pendingTransaction = Transaction::create([
            'user_id' => $matchingCustomer->id,
            'total_bayar' => 50000,
            'status_pembayaran' => 'pending',
            'payment_method' => 'test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.tickets'))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->count() === 10);

        $this->actingAs($admin)
            ->get(route('admin.tickets', [
                'search' => 'Siti',
                'approval_status' => 'pending',
            ]))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->count() === 1
                && $transactions->first()->is($pendingTransaction));

        $this->actingAs($admin)
            ->get(route('admin.tickets', [
                'search' => 'INV-' . str_pad((string) $pendingTransaction->id, 6, '0', STR_PAD_LEFT),
                'approval_status' => 'pending',
            ]))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->count() === 1
                && $transactions->first()->is($pendingTransaction));
    }
}
