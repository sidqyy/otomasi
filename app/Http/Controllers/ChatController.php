<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MessageRepository;
use App\Services\FonnteService;
use App\Models\Contact;
use App\Models\Message;

class ChatController extends Controller
{
    protected $messageRepo;
    protected $fonnteService;

    public function __construct(MessageRepository $messageRepo, FonnteService $fonnteService)
    {
        $this->messageRepo = $messageRepo;
        $this->fonnteService = $fonnteService;
    }

    public function index()
    {
        $contacts = $this->messageRepo->getRecentChats();
        return view('chat.index', compact('contacts'));
    }

    public function show($contactId)
    {
        $contact = Contact::findOrFail($contactId);
        $messages = $contact->messages()->orderBy('created_at', 'asc')->get();
        return response()->json([
            'contact' => $contact,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request, $contactId)
    {
        $contact = Contact::findOrFail($contactId);
        $content = $request->input('message');

        // Store outbound message
        $message = $this->messageRepo->storeOutboundMessage($contactId, $content, 'text', 'admin');

        // Send via Fonnte
        $response = $this->fonnteService->sendText($contact->phone_number, $content);

        if (isset($response['status']) && $response['status']) {
            $message->update(['status' => 'sent', 'message_id' => $response['id'] ?? null]);
        } else {
            $message->update(['status' => 'failed']);
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
