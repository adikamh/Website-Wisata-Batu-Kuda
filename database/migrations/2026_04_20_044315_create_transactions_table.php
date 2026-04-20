<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_bayar', 12, 2);
            $table->enum('status_pembayaran', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->string('snap_token_midtrans', 255)->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status_pembayaran']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};