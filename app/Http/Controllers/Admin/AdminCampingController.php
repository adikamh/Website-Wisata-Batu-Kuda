<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\RecordsAdminActivity;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminCampingController extends Controller
{
    use RecordsAdminActivity;

    private function authorizeAdmin(): void
    {
        abort_if(! Auth::check() || Auth::user()->role !== 'admin', 403);
    }

    public function approveExit(Request $request, Transaction $transaction)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'actual_count' => ['required', 'integer', 'min:0'],
            'trash_brought' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $detail = $transaction->details()->first();

        if (! $detail || ($detail->package_type ?? '') !== 'camping') {
            return redirect()->route('admin.tickets')->with('status', 'Transaksi ini bukan paket camping.');
        }

        $expected = (int) ($detail->quantity ?? 0);
        $actual = (int) $validated['actual_count'];

        // require that check-in was recorded
        if (! $transaction->camping_checked_in_at) {
            return redirect()->route('admin.tickets')->with('status', 'Catat masuk terlebih dahulu sebelum mencatat keluar.');
        }

        $checkinCount = (int) ($transaction->camping_checked_in_visitor_count ?? 0);

        if ($checkinCount > 0 && $actual !== $checkinCount) {
            return redirect()->route('admin.tickets')->with('status', 'Jumlah keluar tidak sesuai dengan jumlah yang tercatat saat masuk. Verifikasi terlebih dahulu.');
        }

        if ($checkinCount === 0 && $actual !== $expected) {
            return redirect()->route('admin.tickets')->with('status', 'Jumlah tiket aktual tidak sesuai jumlah yang dipesan. Verifikasi terlebih dahulu.');
        }

        // determine stay days (inclusive)
        $stayDays = 0;

        if ($detail->start_date && $detail->end_date) {
            try {
                $start = Carbon::parse($detail->start_date);
                $end = Carbon::parse($detail->end_date);
                $stayDays = $start->diffInDays($end) + 1;
            } catch (\Exception $e) {
                $stayDays = (int) ($detail->total_days ?? 0);
            }
        } else {
            $stayDays = (int) ($detail->total_days ?? 0);
        }

        $trashBrought = (bool) ($validated['trash_brought'] ?? false);
        $penalty = 0;
        $reason = null;

        if ($stayDays > 3) {
            $penalty = 150000; // Rp 150.000
            $reason = 'Melebihi 3 hari menginap';
        }

        if ($trashBrought) {
            $penalty = 0;
            $reason = 'Sampah dibawa - pengecualian denda';
        }

        DB::transaction(function () use ($transaction, $actual, $trashBrought, $penalty, $reason) {
            // lock and refresh
            $tx = Transaction::where('id', $transaction->id)->lockForUpdate()->first();
            // ensure checkin counts persisted as well
            $tx->camping_checked_in_visitor_count = $tx->camping_checked_in_visitor_count ?? $tx->camping_checked_in_visitor_count;

            $tx->camping_checked_out_at = now();
            $tx->camping_trash_taken = $trashBrought;
            $tx->camping_actual_visitor_count = $actual;
            $tx->camping_penalty = $penalty;
            $tx->camping_penalty_reason = $reason;
            $tx->save();

            if ($penalty > 0) {
                $tx->increment('total_bayar', $penalty);
            }
        });

        $this->recordAdminActivity(
            'camping_exit_approved',
            'Mencatat checkout camping INV-' . str_pad((string) $transaction->id, 6, '0', STR_PAD_LEFT) . ' (denda: Rp' . number_format($penalty, 0, ',', '.') . ')',
            $transaction,
            [
                'icon' => 'fa-campground',
                'icon_bg' => 'bg-emerald-100',
                'icon_text' => 'text-emerald-600',
            ]
        );

        return redirect()->route('admin.tickets')->with('status', 'Checkout camping dicatat.' . ($penalty > 0 ? ' Denda: Rp' . number_format($penalty, 0, ',', '.') : ''));
    }

    public function markAsCamping(Request $request, Transaction $transaction)
    {
        $this->authorizeAdmin();

        $detail = $transaction->details()->first();

        if (! $detail) {
            return redirect()->route('admin.tickets')->with('status', 'Transaksi tidak memiliki detail tiket.');
        }

        if (($detail->package_type ?? '') === 'camping') {
            return redirect()->route('admin.tickets')->with('status', 'Transaksi sudah bertipe camping.');
        }

        $detail->update(['package_type' => 'camping']);

        $this->recordAdminActivity('mark_camping', 'Menandai transaksi INV-' . str_pad((string) $transaction->id, 6, '0', STR_PAD_LEFT) . ' sebagai paket camping', $transaction);

        return redirect()->route('admin.tickets')->with('status', 'Transaksi berhasil ditandai sebagai paket camping.');
    }

    public function markCheckIn(Request $request, Transaction $transaction)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'checkin_count' => ['required', 'integer', 'min:0'],
        ]);

        $detail = $transaction->details()->first();

        if (! $detail || ($detail->package_type ?? '') !== 'camping') {
            return redirect()->route('admin.tickets')->with('status', 'Transaksi ini bukan paket camping.');
        }

        $expected = (int) ($detail->quantity ?? 0);
        $count = (int) $validated['checkin_count'];

        if ($count !== $expected) {
            return redirect()->route('admin.tickets')->with('status', 'Jumlah check-in tidak sesuai jumlah yang dipesan.');
        }

        DB::transaction(function () use ($transaction, $count) {
            $tx = Transaction::where('id', $transaction->id)->lockForUpdate()->first();
            $tx->camping_checked_in_at = now();
            $tx->camping_checked_in_visitor_count = $count;
            $tx->save();
        });

        $this->recordAdminActivity('camping_checkin', 'Mencatat check-in camping INV-' . str_pad((string) $transaction->id, 6, '0', STR_PAD_LEFT), $transaction, [
            'icon' => 'fa-door-open',
            'icon_bg' => 'bg-emerald-100',
            'icon_text' => 'text-emerald-600',
        ]);

        return redirect()->route('admin.tickets')->with('status', 'Check-in camping berhasil dicatat.');
    }
}
