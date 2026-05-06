<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('tiket_kategori_id')->nullable()->constrained('tiket_kategori')->onDelete('set null');
            $table->foreignId('paket_id')->nullable()->constrained('paket_wisata')->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            
            $table->enum('package_type', ['visit', 'camping'])->default('visit');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('total_days')->default(1);
            $table->integer('extra_days')->default(0);
            $table->decimal('extra_days_charge', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->timestamps();
            
            $table->index(['transaction_id', 'package_type']);
            $table->index('start_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};