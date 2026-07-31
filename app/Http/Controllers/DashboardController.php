<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Contact;
use App\Models\WebhookLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_chat_today' => Message::whereDate('created_at', today())->count(),
            'total_customer' => Contact::count(),
            'total_chat_active' => Message::where('status', '!=', 'read')->count(), // simplistic view
            'total_chat_completed' => Message::where('status', 'read')->count(),
            'webhook_status' => WebhookLog::latest()->first()?->status == 'processed' ? 'Active' : 'Warning',
            'fonnte_connection' => 'Connected', // We can ping Fonnte API in real logic
        ];

        return view('dashboard', compact('stats'));
    }
}
