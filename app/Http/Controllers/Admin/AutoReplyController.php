<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReply;
use Illuminate\Http\Request;

class AutoReplyController extends Controller
{
    public function index()
    {
        $replies = AutoReply::latest()->paginate(10);
        return view('admin.auto-replies.index', compact('replies'));
    }

    public function create()
    {
        return view('admin.auto-replies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'required|string|max:255|unique:auto_replies,keyword',
            'match_type' => 'required|in:exact,contains',
            'reply_text' => 'required|string',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        AutoReply::create($validated);
        
        return redirect()->route('admin.auto-replies.index')->with('success', 'Auto Reply created successfully.');
    }

    public function edit(AutoReply $autoReply)
    {
        return view('admin.auto-replies.edit', compact('autoReply'));
    }

    public function update(Request $request, AutoReply $autoReply)
    {
        $validated = $request->validate([
            'keyword' => 'required|string|max:255|unique:auto_replies,keyword,' . $autoReply->id,
            'match_type' => 'required|in:exact,contains',
            'reply_text' => 'required|string',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $autoReply->update($validated);
        
        return redirect()->route('admin.auto-replies.index')->with('success', 'Auto Reply updated successfully.');
    }

    public function destroy(AutoReply $autoReply)
    {
        $autoReply->delete();
        return redirect()->route('admin.auto-replies.index')->with('success', 'Auto Reply deleted successfully.');
    }
}
