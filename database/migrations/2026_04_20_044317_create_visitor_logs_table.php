<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('e_ticket_id')->constrained('e_tickets')->onDelete('cascade');
            $table->string('visitor_name', 100);
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['e_ticket_id', 'scanned_at']);
            $table->index('scanned_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_logs');
    }
};