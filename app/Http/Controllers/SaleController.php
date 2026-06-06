<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('buyer', 'saleDetails.product');

        if ($request->filled('search')) {
            $query->whereHas('buyer', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('sale_date', '<=', $request->date_to);
        }

        $sales = $query->latest()->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $buyers = Buyer::orderBy('name')->get();
        $products = Product::where('quantity', '>', 0)->orderBy('name')->get();

        return view('sales.create', compact('buyers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'sale_date' => 'required|date',
            'payment_status' => 'required|in:pending,paid,partial',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'buyer_id' => $validated['buyer_id'],
                'sale_date' => $validated['sale_date'],
                'payment_status' => $validated['payment_status'],
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    DB::rollBack();

                    return back()->withErrors([
                        'error' => "Insufficient stock for product: {$product->name}. Available: {$product->quantity}, Requested: {$item['quantity']}",
                    ])->withInput();
                }

                $subtotal = $product->selling_price * $item['quantity'];
                $totalAmount += $subtotal;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('quantity', $item['quantity']);
            }

            $sale->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create sale', [
                'buyer_id' => $validated['buyer_id'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Failed to record sale. Please try again or contact support.'])->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('buyer', 'saleDetails.product', 'delivery');

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $buyers = Buyer::orderBy('name')->get();
        $products = Product::where(function ($q) use ($sale) {
            $q->where('quantity', '>', 0)
                ->orWhereHas('saleDetails', function ($q) use ($sale) {
                    $q->where('sale_id', $sale->id);
                });
        })->orderBy('name')->get();
        $sale->load('saleDetails.product');

        return view('sales.edit', compact('sale', 'buyers', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,partial',
        ]);

        $sale->update(['payment_status' => $validated['payment_status']]);

        return redirect()->route('sales.index')
            ->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Eager load details with products for stock restoration
            $sale->load('saleDetails.product');

            // Restore product quantities
            foreach ($sale->saleDetails as $detail) {
                if ($detail->product) {
                    $detail->product->increment('quantity', $detail->quantity);
                }
            }

            $sale->saleDetails()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete sale', [
                'sale_id' => $sale->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Failed to delete sale: '.$e->getMessage()]);
        }
    }
}
