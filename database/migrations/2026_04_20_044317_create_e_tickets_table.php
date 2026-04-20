<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('e_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_detail_id')->constrained('transaction_details')->onDelete('cascade');
            $table->string('ticket_code', 50)->unique();
            $table->string('qr_code_hash', 255)->unique();
            $table->string('watermark_path', 255)->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->index(['ticket_code', 'qr_code_hash']);
            $table->index('is_used');
        });
    }

    public function down()
    {
        Schema::dropIfExists('e_tickets');
    }
};