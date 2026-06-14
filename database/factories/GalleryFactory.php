<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition()
    {
        return [
            'judul_foto' => $this->faker->sentence(3),
            'deskripsi'  => $this->faker->paragraph(),
            'gambar_url' => 'test.jpg',
        ];
    }
}
