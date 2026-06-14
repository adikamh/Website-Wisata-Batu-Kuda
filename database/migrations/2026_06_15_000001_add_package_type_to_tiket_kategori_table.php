<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_kategori', function (Blueprint $table) {
            $table->enum('package_type', ['visit', 'camping'])
                ->default('visit')
                ->after('deskripsi');
        });

        DB::table('tiket_kategori')
            ->where(function ($query) {
                $query->whereRaw('LOWER(nama_kategori) LIKE ?', ['%camping%'])
                    ->orWhereRaw('LOWER(nama_kategori) LIKE ?', ['%kemping%']);
            })
            ->update(['package_type' => 'camping']);
    }

    public function down(): void
    {
        Schema::table('tiket_kategori', function (Blueprint $table) {
            $table->dropColumn('package_type');
        });
    }
};
