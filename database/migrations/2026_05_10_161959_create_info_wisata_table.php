<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_wisata', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('kategori', 80)->nullable();
            $table->string('icon', 10)->nullable();         // emoji
            $table->text('deskripsi')->nullable();
            $table->json('poin')->nullable();               // [{judul, isi}, ...]
            $table->json('gambar')->nullable();             // [url, url, ...]
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_wisata');
    }
};