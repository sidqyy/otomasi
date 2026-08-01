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

                // === LOGIKA CHATBOT ===
                $replyText = null;

                // 1. Cek Auto Reply (Exact Match)
                $autoReply = \App\Models\AutoReply::where('keyword', $messageText)
                                ->where('match_type', 'exact')
                                ->where('is_active', true)->first();

                // 2. Cek Auto Reply (Contains Match)
                if (!$autoReply) {
                    $autoReply = \App\Models\AutoReply::where('match_type', 'contains')
                                    ->where('is_active', true)
                                    ->get()
                                    ->first(function($reply) use ($messageText) {
                                        return str_contains($messageText, strtolower($reply->keyword));
                                    });
                }

                if ($autoReply) {
                    $replyText = $autoReply->reply_text;
                }

                // 3. Cek FAQ
                if (!$replyText) {
                    $faq = \App\Models\Faq::where('is_active', true)
                            ->get()
                            ->first(function($f) use ($messageText) {
                                // Pencarian sederhana, apakah kata-kata di pertanyaan ada di pesan
                                return str_contains($messageText, strtolower($f->question));
                            });
                    if ($faq) {
                        $replyText = $faq->answer;
                    }
                }

                // 4. Katalog Produk Sederhana (Fallback Tradisional)
                if (!$replyText && (str_contains($messageText, 'produk') || str_contains($messageText, 'harga') || str_contains($messageText, 'katalog'))) {
                    $products = \App\Models\Product::where('is_ready', true)->take(5)->get();
                    if ($products->count() > 0) {
                        $replyText = "Berikut adalah beberapa produk unggulan kami:\n\n";
                        foreach($products as $prod) {
                            $replyText .= "- *{$prod->name}* : Rp" . number_format($prod->price, 0, ',', '.') . "\n";
                        }
                        $replyText .= "\nKetik nama produk untuk info lebih detail.";
                    }
                }

                // 5. Integrasi Google Gemini AI DIMATIKAN
                // Sengaja dibiarkan kosong agar Fonnte AI bawaan bisa mengambil alih
                if (!$replyText) {
                    return; 
                }

                // Jika ada balasan otomatis, kirim via Fonnte
                if ($replyText) {
                    // Simpan pesan keluar
                    $outboundMessage = $this->messageRepo->storeOutboundMessage($contact->id, $replyText, 'text', 'bot');
                    
                    // Panggil FonnteService
                    $fonnteService = app(\App\Services\FonnteService::class);
                    $response = $fonnteService->sendText($sender, $replyText);

                    if (isset($response['status']) && $response['status']) {
                        $outboundMessage->update(['status' => 'sent', 'message_id' => $response['id'] ?? null]);
                    } else {
                        $outboundMessage->update(['status' => 'failed']);
                    }
                }

            }

        } catch (\Exception $e) {
            Log::error('Webhook Handling Error: ' . $e->getMessage());
        }
    }
}
