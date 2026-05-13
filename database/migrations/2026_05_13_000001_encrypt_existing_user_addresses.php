<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->select(['id', 'Address'])
            ->whereNotNull('Address')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    if ($user->Address === '') {
                        continue;
                    }

                    try {
                        Crypt::decryptString($user->Address);
                        continue;
                    } catch (DecryptException) {
                        // Existing plaintext addresses are encrypted below.
                    }

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['Address' => Crypt::encryptString($user->Address)]);
                }
            });
    }

    public function down(): void
    {
        DB::table('users')
            ->select(['id', 'Address'])
            ->whereNotNull('Address')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    try {
                        $address = Crypt::decryptString($user->Address);
                    } catch (DecryptException) {
                        continue;
                    }

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['Address' => $address]);
                }
            });
    }
};
