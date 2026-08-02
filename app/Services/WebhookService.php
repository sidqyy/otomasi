<?php

namespace App\Services;

use App\Repositories\MessageRepository;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    protected $messageRepo;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    public function handleFonnteWebhook($payload)
    {
        try {
            $device = $payload['device'] ?? null;
            $sender = $payload['sender'] ?? null;
            $messageTextRaw = $payload['message'] ?? null;

            // 1. Anti-Loop: Jangan balas pesan yang dikirim oleh bot itu sendiri
            if ($device && $sender && $device == $sender) {
                return;
            }

            // 1b. Anti-Loop Ekstrem: Fonnte (versi gratis) memantulkan pesan keluar kita sebagai pesan masuk
            // dan selalu menambahkan tulisan "_Sent via fonnte.com_". Kita harus abaikan pesan pantulan ini!
            if ($messageTextRaw && str_contains(strtolower($messageTextRaw), 'sent via fonnte')) {
                Log::info("Fonnte outbound echo blocked: {$messageTextRaw}");
                return;
            }

            // 2. Anti-Spam: Cek apakah pesan yang sama dari orang yang sama masuk dalam 15 detik terakhir
            if ($sender && $messageTextRaw) {
                $isDuplicate = WebhookLog::where('payload->sender', $sender)
                                ->where('payload->message', $messageTextRaw)
                                ->where('created_at', '>=', now()->subSeconds(15))
                                ->exists();
                
                if ($isDuplicate) {
                    Log::info("Spam/Duplicate webhook blocked from: {$sender}");
                    return; // Stop, ini pesan duplikat karena Fonnte retry!
                }
            }

            // Simpan log raw data
            WebhookLog::create([
                'payload' => $payload,
                'event' => $payload['reason'] ?? 'incoming_message',
                'status' => 'processed'
            ]);

            // Cek jika ini adalah pesan masuk
            if (isset($payload['sender']) && isset($payload['message'])) {
                $sender = $payload['sender']; // Nomor pengirim
                $messageText = strtolower(trim($payload['message']));
                $pushName = $payload['name'] ?? null;

                if (isset($payload['isGroup']) && $payload['isGroup'] == 'true') {
                    return;
                }

                $contact = $this->messageRepo->findOrCreateContact($sender, null, $pushName);
                
                // Simpan pesan masuk
                $this->messageRepo->storeInboundMessage($contact->id, $payload['message']);

                // === LOGIKA CHATBOT DIMATIKAN ===
                // Webhook ini murni hanya berfungsi sebagai "logger" (penampung data masuk).
                // Semua balasan (Auto-Reply, FAQ, AI) sepenuhnya diurus oleh dasbor Fonnte.
                
                // Tidak ada balasan yang dikirim dari web ini.
                // return null / 200 OK secara otomatis.

            }

        } catch (\Exception $e) {
            Log::error('Webhook Handling Error: ' . $e->getMessage());
        }
    }
}
