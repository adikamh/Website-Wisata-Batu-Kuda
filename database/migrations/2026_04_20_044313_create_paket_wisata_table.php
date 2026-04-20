<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paket_wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 100);
            $table->text('deskripsi_paket');
            $table->decimal('harga_paket', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paket_wisata');
    }
};