<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketKategori;
use App\Models\Transaction;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTicketController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $tickets = TiketKategori::query()
            ->latest()
            ->get();

        $transactions = Transaction::query()
            ->with(['user', 'details.tiketKategori'])
            ->latest()
            ->limit(20)
            ->get();

        return view()->file(
            resource_path('views/Admin/admin.dashboard.blade.php'),
            compact('tickets', 'transactions')
        );
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

    private function reportTransactions()
    {
        return Transaction::query()
            ->with(['user', 'details.tiketKategori'])
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
            ->to(route('admin.dashboard') . '#tiket')
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
