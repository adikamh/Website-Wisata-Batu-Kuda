<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LikeFoto;

class LikeFotoSeeder extends Seeder
{
    public function run(): void
    {
        // Data like (tanpa perlu cek existing karena unique constraint sudah menjaganya)
        $likes = [
            ['gallery_id' => 1, 'user_id' => 1],
            ['gallery_id' => 1, 'user_id' => 2],
            ['gallery_id' => 1, 'user_id' => 3],
            ['gallery_id' => 2, 'user_id' => 1],
            ['gallery_id' => 2, 'user_id' => 2],
            ['gallery_id' => 3, 'user_id' => 1],
            ['gallery_id' => 3, 'user_id' => 3],
            ['gallery_id' => 4, 'user_id' => 2],
            ['gallery_id' => 4, 'user_id' => 3],
            ['gallery_id' => 5, 'user_id' => 1],
            ['gallery_id' => 5, 'user_id' => 3],
            ['gallery_id' => 6, 'user_id' => 2],
        ];

        foreach ($likes as $like) {
            // Gunakan try-catch atau firstOrCreate untuk menghindari error duplicate
            LikeFoto::firstOrCreate([
                'gallery_id' => $like['gallery_id'],
                'user_id' => $like['user_id'],
            ]);
        }
    }
}