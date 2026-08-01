<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = \App\Models\Faq::latest()->paginate(10);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        \App\Models\Faq::create($validated);
        
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ added successfully.');
    }

    public function edit(string $id)
    {
        $faq = \App\Models\Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, string $id)
    {
        $faq = \App\Models\Faq::findOrFail($id);
        
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $faq->update($validated);
        
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(string $id)
    {
        $faq = \App\Models\Faq::findOrFail($id);
        $faq->delete();
        
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
