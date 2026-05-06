<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paket_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket_wisata')->onDelete('cascade');
            $table->string('nama_item', 100);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paket_items');
    }
};