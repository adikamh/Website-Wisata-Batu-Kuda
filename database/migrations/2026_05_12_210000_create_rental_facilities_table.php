<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rental_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->unsignedInteger('total_stok')->default(0);
            $table->unsignedInteger('stok_tersedia')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'stok_tersedia']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_facilities');
    }
};
