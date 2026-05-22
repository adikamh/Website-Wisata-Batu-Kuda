<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('homepage_content', function (Blueprint $table) {
            $table->id();
            
            // About section
                $table->text('about_title')->nullable();
                $table->text('about_subtitle')->nullable();
                $table->text('about_description')->nullable();
            
            // Features (JSON)
            $table->json('features')->nullable();
            
            // Info section
                $table->text('info_location')->nullable();
                $table->text('info_opening_hours')->nullable();
                $table->text('info_ticket_price')->nullable();
                $table->text('info_contact')->nullable();
            
            // Tips (JSON)
            $table->json('tips')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_content');
    }
};
