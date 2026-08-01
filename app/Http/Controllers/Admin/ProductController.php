<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_ready' => 'boolean'
        ]);
        
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name'] . '-' . time());
        $validated['is_ready'] = $request->has('is_ready');
        
        \App\Models\Product::create($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Product added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_ready' => 'boolean'
        ]);
        
        // Update slug only if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name'] . '-' . time());
        }
        
        $validated['is_ready'] = $request->has('is_ready');
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
