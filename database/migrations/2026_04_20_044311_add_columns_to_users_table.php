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
            $table->string('Phone')->nullable()->after('role');
            $table->string('Address')->nullable()->after('Phone');
            $table->string('otp')->nullable()->after('role');
            $table->timestamp('otp_expired_at')->nullable()->after('otp');
            $table->boolean('is_verified')->default(false)->after('otp_expired_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'Phone', 'Address', 'otp', 'otp_expired_at', 'is_verified']);
        });
    }
};
