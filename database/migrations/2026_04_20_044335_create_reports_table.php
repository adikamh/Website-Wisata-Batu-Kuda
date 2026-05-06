<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            
            // Tambahan kolom untuk watermark & audit
            $table->string('exported_by_name');
            $table->string('exported_by_email');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('exported_at')->useCurrent();
            
            $table->string('tipe_laporan', 50);
            $table->string('file_url', 255);
            $table->timestamps();
            
            $table->index(['admin_id', 'created_at']);
            $table->index('exported_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};