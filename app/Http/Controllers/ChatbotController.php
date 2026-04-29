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
                ['role' => 'user', 'content' => $userInput],
            ],
        ]);

    return response()->json([
        'reply' => $response->json()['choices'][0]['message']['content']
    ]);
}
}
