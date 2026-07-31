<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\Message;

class MessageRepository
{
    public function findOrCreateContact($phoneNumber, $name = null, $pushName = null)
    {
        return Contact::firstOrCreate(
            ['phone_number' => $phoneNumber],
            ['name' => $name, 'push_name' => $pushName, 'is_active' => true]
        );
    }

    public function storeInboundMessage($contactId, $content, $type = 'text', $mediaUrl = null)
    {
        return Message::create([
            'contact_id' => $contactId,
            'type' => $type,
            'content' => $content,
            'media_url' => $mediaUrl,
            'direction' => 'inbound',
            'status' => 'delivered',
            'replied_by' => 'system'
        ]);
    }

    public function storeOutboundMessage($contactId, $content, $type = 'text', $repliedBy = 'bot')
    {
        return Message::create([
            'contact_id' => $contactId,
            'type' => $type,
            'content' => $content,
            'direction' => 'outbound',
            'status' => 'pending',
            'replied_by' => $repliedBy
        ]);
    }

    public function getRecentChats()
    {
        return Contact::with(['messages' => function($query) {
            $query->orderBy('created_at', 'desc')->take(1);
        }])->orderBy('updated_at', 'desc')->get();
    }
}
