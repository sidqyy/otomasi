<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        // Tetap menggunakan variable gemini_api_key agar tidak perlu repot mengubah .env dan DB
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generateReply($userMessage, $systemContext = '')
    {
        if (empty($this->apiKey)) {
            Log::warning('Groq API Key is not set.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemContext
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content'])) {
                    return trim($data['choices'][0]['message']['content']);
                }
            } else {
                Log::error('Groq API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Groq Service Exception: ' . $e->getMessage());
        }

        return null;
    }
}
