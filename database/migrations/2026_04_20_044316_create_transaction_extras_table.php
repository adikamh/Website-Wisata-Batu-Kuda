<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_detail_id')->constrained('transaction_details')->onDelete('cascade');
            $table->enum('extra_name', ['tent', 'hammock', 'matras', 'wifi']);
            $table->decimal('price_per_unit', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->index(['transaction_detail_id', 'extra_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_extras');
    }
};