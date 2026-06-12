<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransactionDetail;

$details = TransactionDetail::query()->where('tiket_kategori_id', 8)->with('transaction','tiketKategori')->get();

if ($details->isEmpty()) { echo "NO_DETAILS_FOR_CAT_8\n"; exit; }

foreach ($details as $d) {
    echo "Detail ID: {$d->id} | Tx: {$d->transaction_id} (status: " . ($d->transaction->status_pembayaran ?? '-') . ") | package_type: {$d->package_type} | start: " . ($d->start_date?->format('Y-m-d') ?? '-') . " | end: " . ($d->end_date?->format('Y-m-d') ?? '-') . " | total_days: {$d->total_days}\n";
}
