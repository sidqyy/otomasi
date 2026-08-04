<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhacenterService
{
    protected $deviceId;
    protected $baseUrl = 'https://app.whacenter.com/api';

    public function __construct()
    {
        $this->deviceId = config('services.whacenter.device_id');
    }

    protected function sendRequest($endpoint, $data = [])
    {
        if (empty($this->deviceId)) {
            Log::error('WhacenterService Error: device_id is not set.');
            return ['status' => false, 'reason' => 'device_id is empty'];
        }

        try {
            $response = Http::asForm()->post($this->baseUrl . $endpoint . '?device_id=' . $this->deviceId, $data);
            
            if (!$response->successful()) {
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
        return $this->sendRequest('/send', [
            'number' => $number,
            'message' => $message,
        ]);
    }
}
