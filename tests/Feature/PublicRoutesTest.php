<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Gallery;
use App\Models\InfoWisata;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_200()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_gallery_index_shows_photos()
    {
        Gallery::factory()->count(3)->create();

        $response = $this->get('/gallery');

        $response->assertStatus(200);
        $this->assertStringContainsString(
            Gallery::first()->judul_foto,
            $response->getContent()
        );
    }

    public function test_gallery_show_returns_200()
    {
        $g = Gallery::factory()->create(['judul_foto' => 'Foto Test Show']);

        $response = $this->get('/gallery/' . $g->id);

        $response->assertStatus(200);
        $this->assertStringContainsString('Foto Test Show', $response->getContent());
    }

    public function test_infowisata_index_shows_sections()
    {
        $info = InfoWisata::factory()->create(['judul' => 'Tentang Test']);

        $response = $this->get('/infowisata');

        $response->assertStatus(200);
        $this->assertStringContainsString('Tentang Test', $response->getContent());
    }
}
