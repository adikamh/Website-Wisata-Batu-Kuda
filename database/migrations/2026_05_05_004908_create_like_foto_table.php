<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('like_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('gallery')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Biar 1 user cuma bisa like 1x per foto
            $table->unique(['gallery_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('like_foto');
    }
};