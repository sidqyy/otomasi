<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function fonnte(Request $request)
    {
        try {
            $payload = $request->all();
            
            // Fonnte mengirimkan webhook berupa form-data atau json
            $this->webhookService->handleFonnteWebhook($payload);

            return response()->json(['status' => 'success', 'message' => 'Webhook received']);
        } catch (\Exception $e) {
            Log::error('Fonnte Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
