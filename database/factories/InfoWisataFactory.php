<?php

namespace Database\Factories;

use App\Models\InfoWisata;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfoWisataFactory extends Factory
{
    protected $model = InfoWisata::class;

    public function definition()
    {
        return [
            'judul'     => $this->faker->words(3, true),
            'kategori'  => 'umum',
            'icon'      => null,
            'deskripsi' => $this->faker->sentence(),
            'poin'      => [],
            'gambar'    => [],
            'urutan'    => 0,
        ];
    }
}
