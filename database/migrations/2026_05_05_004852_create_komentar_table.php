<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('gallery')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('isi_komentar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('komentar');
    }
};