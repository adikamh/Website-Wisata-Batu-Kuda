<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('rental_facility_id')->nullable()->constrained('rental_facilities')->onDelete('set null');
            $table->string('facility_name', 100);
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_rental_items');
    }
};
