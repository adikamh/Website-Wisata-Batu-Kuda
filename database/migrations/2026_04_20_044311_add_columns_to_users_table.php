<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->string('role')->default('user')->after('password');
            $table->string('otp_code', 6)->nullable()->after('role');
            $table->boolean('is_verified')->default(false)->after('otp_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'otp_code', 'is_verified']);
        });
    }
};