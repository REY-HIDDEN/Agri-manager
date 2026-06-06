<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with('sale.buyer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deliveries = $query->latest()->paginate(10);

        return view('deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        $sales = Sale::with('buyer')
            ->whereDoesntHave('delivery')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('deliveries.create', compact('sales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id|unique:deliveries,sale_id',
            'delivery_date' => 'required|date',
            'destination' => 'required|string|max:500',
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        Delivery::create($validated);

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery recorded successfully.');
    }

    public function edit(Delivery $delivery)
    {
        $delivery->load('sale.buyer');

        return view('deliveries.edit', compact('delivery'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'destination' => 'required|string|max:500',
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        $delivery->update($validated);

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery updated successfully.');
    }

    public function destroy(Delivery $delivery)
    {
        try {
            $delivery->delete();

            return redirect()->route('deliveries.index')
                ->with('success', 'Delivery deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete delivery', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to delete delivery. Please try again.']);
        }
    }
}
