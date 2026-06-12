<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaction;

$transactions = Transaction::query()
    ->with(['user','details'])
    ->whereHas('details', function($q){ $q->where('package_type','camping'); })
    ->orderByDesc('id')
    ->limit(50)
    ->get();

if ($transactions->isEmpty()) {
    echo "NO_CAMPING_TX\n";
    exit(0);
}

foreach ($transactions as $t) {
    $d = $t->details->first();
    echo "ID: {$t->id} | INV-" . str_pad($t->id,6,'0',STR_PAD_LEFT) . " | user: " . ($t->user->name ?? '-') . " | status: {$t->status_pembayaran} | start: " . ($d?->start_date?->format('Y-m-d') ?? '-') . " | end: " . ($d?->end_date?->format('Y-m-d') ?? '-') . " | checked_out_at: " . ($t->camping_checked_out_at?->format('Y-m-d H:i:s') ?? 'NULL') . " | penalty: {$t->camping_penalty}\n";
}
