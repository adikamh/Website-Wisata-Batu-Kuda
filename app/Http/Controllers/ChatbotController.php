<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __invoke(Request $request)
    {
        $userInput = $request->input('message');

        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah Baku, asisten ramah untuk Wisata Batu Kuda, Bandung. Tugas Anda membantu pengunjung mengenai harga tiket (Rp10rb-15rb), jam buka (08.00-17.00), rute, dan fasilitas camping/trekking. Jawablah dengan singkat dan informatif dalam Bahasa Indonesia. dan jangan jawab topik selain wisata batu kuda'
                    ],
                    ['role' => 'user', 'content' => $userInput],
                ],
            ]);

        $data = $response->json();

        if (isset($data['error'])) {
            return response()->json([
                'reply' => 'Error dari API: ' . $data['error']['message']
            ], 500);
        }

        return response()->json([
            'reply' => $data['choices'][0]['message']['content'] ?? 'Maaf, respons kosong.'
        ]);
    }
}