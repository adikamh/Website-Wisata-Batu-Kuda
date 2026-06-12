<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('camping_checked_out_at')->nullable()->after('status_pembayaran');
            $table->boolean('camping_trash_taken')->nullable()->after('camping_checked_out_at');
            $table->integer('camping_actual_visitor_count')->nullable()->after('camping_trash_taken');
            $table->integer('camping_penalty')->default(0)->after('camping_actual_visitor_count');
            $table->string('camping_penalty_reason')->nullable()->after('camping_penalty');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'camping_checked_out_at',
                'camping_trash_taken',
                'camping_actual_visitor_count',
                'camping_penalty',
                'camping_penalty_reason',
            ]);
        });
    }
};
