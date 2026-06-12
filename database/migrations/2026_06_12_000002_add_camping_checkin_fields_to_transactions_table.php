<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('camping_checked_in_at')->nullable()->after('camping_checked_out_at');
            $table->integer('camping_checked_in_visitor_count')->nullable()->after('camping_checked_in_at');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['camping_checked_in_at', 'camping_checked_in_visitor_count']);
        });
    }
};
