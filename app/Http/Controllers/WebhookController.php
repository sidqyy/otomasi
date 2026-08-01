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
            
            // Proses di background agar tidak timeout di Fonnte (menghindari spam retry)
            if (function_exists('fastcgi_finish_request')) {
                dispatch(function () use ($payload) {
                    app(\App\Services\WebhookService::class)->handleFonnteWebhook($payload);
                })->afterResponse();
            } else {
                $this->webhookService->handleFonnteWebhook($payload);
            }

            return response()->json(['status' => 'success', 'message' => 'Webhook received']);
        } catch (\Exception $e) {
            Log::error('Fonnte Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
