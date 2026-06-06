<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Filter by quantity range
        if ($request->filled('quantity_min')) {
            $query->where('quantity', '>=', $request->quantity_min);
        }
        if ($request->filled('quantity_max')) {
            $query->where('quantity', '<=', $request->quantity_max);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('selling_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('selling_price', '<=', $request->price_max);
        }

        // Low stock filter
        if ($request->boolean('low_stock')) {
            $query->where('quantity', '<', 10);
        }

        if ($request->filled('sort')) {
            $allowedSorts = ['name', 'quantity', 'buying_price', 'selling_price', 'created_at', 'updated_at'];
            $sortColumn = in_array($request->sort, $allowedSorts) ? $request->sort : 'created_at';
            $sortDirection = in_array($request->direction, ['asc', 'desc']) ? $request->direction : 'asc';
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'quantity' => 'required|integer|min:0',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'quantity' => 'required|integer|min:0',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->saleDetails()->exists()) {
            return back()->withErrors(['error' => "Cannot delete product '{$product->name}' because it has associated sales records."]);
        }

        try {
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete product', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to delete product. Please try again.']);
        }
    }
}
