<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom untuk integrasi Xendit
            $table->string('xendit_invoice_id')->nullable()->after('snap_token_midtrans');
            $table->string('xendit_external_id')->nullable()->after('xendit_invoice_id');
            $table->string('xendit_invoice_url')->nullable()->after('xendit_external_id');
            $table->json('xendit_response')->nullable()->after('xendit_invoice_url');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'xendit_invoice_id',
                'xendit_external_id',
                'xendit_invoice_url',
                'xendit_response',
            ]);
        });
    }
};
