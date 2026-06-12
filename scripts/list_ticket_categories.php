<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TiketKategori;

$cats = TiketKategori::query()->orderBy('id')->get();
if ($cats->isEmpty()) { echo "NO_CAT\n"; exit; }
foreach ($cats as $c) {
    echo "ID: {$c->id} | {$c->nama_kategori} | harga: {$c->harga}\n";
}
