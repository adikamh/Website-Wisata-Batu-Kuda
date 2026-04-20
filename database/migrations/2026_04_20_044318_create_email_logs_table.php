<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('e_ticket_id')->constrained('e_tickets')->onDelete('cascade');
            $table->string('sent_to_email', 100);
            $table->timestamp('sent_at')->useCurrent();
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['e_ticket_id', 'status']);
            $table->index('sent_to_email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
};