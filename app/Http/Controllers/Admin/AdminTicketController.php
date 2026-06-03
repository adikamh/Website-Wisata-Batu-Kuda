<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketKategori;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminTicketController extends Controller
{
    public function dashboard()
    {
        $this->authorizeAdmin();

        $successfulTransactions = fn () => Transaction::query()
            ->where('status_pembayaran', 'success');

        $successfulTransactionDetails = fn () => TransactionDetail::query()
            ->whereHas('transaction', fn ($query) => $query->where('status_pembayaran', 'success'));

        $dailyRevenue = $successfulTransactions()
            ->selectRaw('DATE(created_at) as transaction_date, SUM(total_bayar) as total_revenue')
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total_revenue', 'transaction_date');

        $chartLabels = collect(range(6, 0))
            ->map(fn (int $daysAgo) => now()->subDays($daysAgo)->locale('id')->translatedFormat('D'))
            ->values();

        $chartData = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($dailyRevenue) {
                $date = now()->subDays($daysAgo)->toDateString();

                return (float) ($dailyRevenue[$date] ?? 0);
            })
            ->values();

        $recentActivities = collect()
            ->merge(
                User::query()
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(fn (User $user) => [
                        'type' => 'user',
                        'icon' => 'fa-user-plus',
                        'icon_bg' => 'bg-green-100',
                        'icon_text' => 'text-green-600',
                        'title' => $user->name,
                        'description' => 'Pengguna baru terdaftar',
                        'time' => $user->created_at,
                    ])
            )
            ->merge(
                Transaction::query()
                    ->with(['user', 'details', 'rentalItems'])
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(fn (Transaction $transaction) => [
                        'type' => 'transaction',
                        'icon' => 'fa-ticket-alt',
                        'icon_bg' => 'bg-indigo-100',
                        'icon_text' => 'text-indigo-600',
                        'title' => $transaction->user->name ?? 'Pengguna',
                        'description' => 'Pembelian tiket ' . ($transaction->details->sum('quantity') ?: 0) . ' item'
                            . ($transaction->rentalItems->isNotEmpty() ? ' + sewa fasilitas' : ''),
                        'time' => $transaction->created_at,
                    ])
            )
            ->sortByDesc('time')
            ->take(5)
            ->values();

        $stats = [
            'total_users' => User::count(),
            'today_revenue' => (float) $successfulTransactions()
                ->whereDate('created_at', today())
                ->sum('total_bayar'),
            'tickets_sold' => (int) $successfulTransactionDetails()
                ->sum('quantity'),
            'camping_orders' => (int) $successfulTransactionDetails()
                ->where('package_type', 'camping')
                ->count(),
        ];

        return view('Admin.dashboard', compact('stats', 'chartLabels', 'chartData', 'recentActivities'));
    }

    public function users()
    {
        $this->authorizeAdmin();

        $users = User::query()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Admin.users.index', compact('users'));
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $tickets = TiketKategori::query()
            ->latest()
            ->get();

        $transactionFilters = [
            'search' => trim((string) $request->query('search', '')),
            'approval_status' => $request->query('approval_status', 'all'),
        ];

        if (! in_array($transactionFilters['approval_status'], ['all', 'pending', 'success'], true)) {
            $transactionFilters['approval_status'] = 'all';
        }

        $transactions = Transaction::query()
            ->with(['user', 'details.tiketKategori', 'rentalItems'])
            ->when($transactionFilters['search'] !== '', function ($query) use ($transactionFilters) {
                $search = $transactionFilters['search'];
                $receiptId = preg_replace('/\D/', '', $search);

                $query->where(function ($query) use ($search, $receiptId) {
                    $query->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));

                    if ($receiptId !== '') {
                        $query->orWhere('transactions.id', (int) $receiptId);
                    }
                });
            })
            ->when($transactionFilters['approval_status'] !== 'all', fn ($query) => $query->where('status_pembayaran', $transactionFilters['approval_status']))
            ->latest()
            ->limit(10)
            ->get();

        return view('Admin.tickets.index', compact('tickets', 'transactions', 'transactionFilters'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateTicket($request);

        TiketKategori::create([
            'wisata_id' => $this->batuKuda()->id,
            'nama_kategori' => $validated['nama_kategori'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga' => $validated['harga'],
        ]);

        return $this->redirectToTickets('Tiket berhasil ditambahkan.');
    }

    public function storeUser(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateUser($request);

        User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users')
            ->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $this->validateUser($request, $user);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users')
            ->with('status', 'Pengguna berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        $this->authorizeAdmin();

        abort_if(Auth::id() === $user->id, 422, 'Akun admin yang sedang login tidak bisa dihapus.');

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('status', 'Pengguna berhasil dihapus.');
    }

    public function update(Request $request, TiketKategori $ticket)
    {
        $this->authorizeAdmin();

        $ticket->update($this->validateTicket($request));

        return $this->redirectToTickets('Tiket berhasil diperbarui.');
    }

    public function destroy(TiketKategori $ticket)
    {
        $this->authorizeAdmin();

        $ticket->delete();

        return $this->redirectToTickets('Tiket berhasil dihapus.');
    }

    public function approveTransaction(Transaction $transaction)
    {
        $this->authorizeAdmin();

        if ($transaction->status_pembayaran !== 'pending') {
            return $this->redirectToTickets('Hanya transaksi dengan status pending yang dapat di-approve.');
        }

        $transaction->update([
            'status_pembayaran' => 'success',
        ]);

        $this->recordAdminActivity(
            'ticket_approved',
            'meng-approve transaksi INV-' . str_pad((string) $transaction->id, 6, '0', STR_PAD_LEFT),
            $transaction,
            [
                'icon' => 'fa-check-circle',
                'icon_bg' => 'bg-green-100',
                'icon_text' => 'text-green-600',
            ]
        );

        return $this->redirectToTickets('Transaksi berhasil di-approve dan akan muncul di dashboard.');
    }

    public function downloadVisitorPdf()
    {
        $this->authorizeAdmin();

        $lines = [
            'LAPORAN DAFTAR PENGUNJUNG BATU KUDA',
            'Dicetak: ' . now()->format('d/m/Y H:i'),
            '',
        ];

        foreach ($this->reportTransactions() as $transaction) {
            $detail = $transaction->details->first();
            $lines[] = 'Resi: INV-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT);
            $lines[] = 'Nama: ' . ($transaction->user->name ?? '-');
            $lines[] = 'Email: ' . ($transaction->user->email ?? '-');
            $lines[] = 'Tiket: ' . ($detail?->tiketKategori?->nama_kategori ?? '-');
            $lines[] = 'Jumlah: ' . ($detail->quantity ?? 0) . ' orang';
            $lines[] = 'Tanggal Masuk: ' . ($detail?->start_date?->format('d/m/Y') ?? '-');
            $lines[] = 'Tanggal Keluar: ' . ($detail?->end_date?->format('d/m/Y') ?? '-');
            $lines[] = 'Status: ' . strtoupper($transaction->status_pembayaran);
            $lines[] = str_repeat('-', 72);
        }

        if (count($lines) === 3) {
            $lines[] = 'Belum ada data pengunjung.';
        }

        $filename = 'laporan-daftar-pengunjung-' . now()->format('Ymd-His') . '.pdf';

        return response($this->makeSimplePdf($lines), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function downloadFinanceExcel()
    {
        $this->authorizeAdmin();

        $transactions = $this->reportTransactions();
        $totalRevenue = $transactions->sum('total_bayar');
        $filename = 'laporan-keuangan-' . now()->format('Ymd-His') . '.xls';

        return response()
            ->view('Admin.tickets.reports-excel', compact('transactions', 'totalRevenue'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function validateTicket(Request $request): array
    {
        return $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'harga' => ['required', 'numeric', 'min:0', 'max:99999999'],
        ], [
            'nama_kategori.required' => 'Nama tiket wajib diisi.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            'harga.required' => 'Harga tiket wajib diisi.',
            'harga.numeric' => 'Harga tiket harus berupa angka.',
        ]);
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $passwordRules = $user
            ? ['nullable', 'confirmed', Password::min(8)]
            : ['required', 'confirmed', Password::min(8)];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(['admin', 'user', 'moderator'])],
            'Phone' => ['nullable', 'string', 'max:20'],
            'Address' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
            'password' => $passwordRules,
        ], [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ], [
            'Phone' => 'nomor telepon',
            'Address' => 'alamat',
        ]);

        $validated['is_verified'] = $request->boolean('is_verified');

        return $validated;
    }

    private function reportTransactions()
    {
        return Transaction::query()
            ->with(['user', 'details.tiketKategori', 'rentalItems'])
            ->latest()
            ->get();
    }

    private function makeSimplePdf(array $lines): string
    {
        $objects = [];
        $pages = [];
        $chunks = array_chunk($lines, 38);

        foreach ($chunks as $pageIndex => $pageLines) {
            $content = "BT\n/F1 10 Tf\n50 790 Td\n14 TL\n";

            foreach ($pageLines as $line) {
                $content .= '(' . $this->escapePdfText($line) . ") Tj\nT*\n";
            }

            $content .= "ET\n";
            $contentObjectNumber = 4 + ($pageIndex * 2);
            $pageObjectNumber = $contentObjectNumber + 1;

            $objects[$contentObjectNumber] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream";
            $objects[$pageObjectNumber] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentObjectNumber} 0 R >>";
            $pages[] = "{$pageObjectNumber} 0 R";
        }

        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $pages) . '] /Count ' . count($pages) . ' >>';
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $number => $body) {
            $offsets[$number] = strlen($pdf);
            $pdf .= "{$number} 0 obj\n{$body}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $objectCount = max(array_keys($objects));
        $pdf .= "xref\n0 " . ($objectCount + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= $objectCount; $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        return $pdf . "trailer\n<< /Size " . ($objectCount + 1) . " /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $text);
    }

    private function redirectToTickets(string $message)
    {
        return redirect()
            ->route('admin.tickets')
            ->with('status', $message);
    }

    private function authorizeAdmin(): void
    {
        abort_if(! Auth::check() || Auth::user()->role !== 'admin', 403);
    }

    private function batuKuda(): Wisata
    {
        return Wisata::firstOrCreate(
            ['nama_wisata' => 'Batu Kuda'],
            [
                'deskripsi' => 'Kawasan wisata alam Batu Kuda.',
                'lokasi' => 'Cikadut, Cimenyan, Kabupaten Bandung, Jawa Barat',
                'gambar_url' => asset('images/hero.jpeg'),
            ]
        );
    }
}
