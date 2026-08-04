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
        $url = $this->baseUrl . $endpoint;

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Mengirim sebagai multipart/form-data persis seperti dokumentasi
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Mencegah error SSL
            $result = curl_exec($ch);
            
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                Log::error('Whacenter cURL Error: ' . $error_msg);
                curl_close($ch);
                return ['status' => false, 'reason' => $error_msg];
            }
            curl_close($ch);
            
            $responseArray = json_decode($result, true);
            
            if (!$responseArray || (isset($responseArray['status']) && $responseArray['status'] === false)) {
                Log::error('Whacenter API Failed: ' . $result);
            } else {
                Log::info('Whacenter API Success: ' . $result);
            }
            
            return $responseArray;
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
