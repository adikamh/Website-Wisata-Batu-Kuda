<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'otp_code')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->renameColumn('otp_code', 'otp');
            });
        }

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'otp')) {
                $table->string('otp')->nullable()->after('role');
            }

            if (! Schema::hasColumn('users', 'otp_expired_at')) {
                $table->timestamp('otp_expired_at')->nullable()->after('otp');
            }

            if (! Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('otp_expired_at');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'otp_expired_at')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('otp_expired_at');
            });
        }

        if (Schema::hasColumn('users', 'otp')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('otp');
            });
        }
    }
};
