<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhacenterService
{
    protected $deviceId;
    protected $baseUrl;

    public function __construct()
    {
        $this->deviceId = trim(config('services.whacenter.device_id'));
        $this->baseUrl = trim(config('services.whacenter.base_url', 'https://app.whacenter.com/api'));
        
        // Pastikan tidak ada slash berlebih di akhir URL
        $this->baseUrl = rtrim($this->baseUrl, '/');
    }

    protected function sendRequest($endpoint, $data = [])
    {
        if (empty($this->deviceId)) {
            Log::error('WhacenterService Error: device_id is not set.');
            return ['status' => false, 'reason' => 'device_id is empty'];
        }

        $data['device_id'] = $this->deviceId;

        try {
            $response = Http::post($this->baseUrl . $endpoint, $data);
            
            if (!$response->successful() || (isset($response['status']) && $response['status'] === false)) {
                Log::error('Whacenter API Failed: ' . $response->body());
            } else {
                Log::info('Whacenter API Success: ' . $response->body());
            }
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('WhacenterService Error: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    public function sendText($number, $message)
    {
        // Pastikan hanya angka (menghapus @c.us, +, atau spasi jika ada dari webhook)
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);

        return $this->sendRequest('/send', [
            'number' => $cleanNumber,
            'message' => $message,
        ]);
    }
}
