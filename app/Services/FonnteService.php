<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class FonnteService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        // Token bisa dari file env atau dari database setting
        $this->token = config('services.fonnte.token', env('FONNTE_TOKEN'));
    }

    protected function sendRequest($endpoint, $data = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl . $endpoint, $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('FonnteService Error: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    public function sendText($target, $message)
    {
        return $this->sendRequest('/send', [
            'target' => $target,
            'message' => $message,
        ]);
    }

    public function sendImage($target, $url, $caption = '')
    {
        return $this->sendRequest('/send', [
            'target' => $target,
            'url' => $url,
            'message' => $caption,
        ]);
    }

    public function sendDocument($target, $url, $filename = '')
    {
        return $this->sendRequest('/send', [
            'target' => $target,
            'url' => $url,
            'filename' => $filename,
        ]);
    }

    public function getDevice()
    {
        return $this->sendRequest('/device');
    }
    
    // Stub for other methods: sendVideo, sendLocation, sendButton, sendList, sendTemplate, sendTyping, sendReaction
}
