<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with([
            'supplier',
            'user',
            'purchaseItems.product',
        ])->latest()->get();

        return view(
            'admin.purchases.index',
            compact('purchases')
        );
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view(
            'admin.purchases.create',
            compact('suppliers', 'products')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'items' => ['required', 'array'],
            'items.*.quantity' => ['nullable', 'integer', 'min:0'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $items = collect($validated['items'])
            ->filter(fn ($item) => (int) ($item['quantity'] ?? 0) > 0);

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Add a quantity for at least one product.',
            ]);
        }

        $purchase = DB::transaction(function () use ($validated, $items) {
            $products = Product::whereIn('id', $items->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== $items->count()) {
                throw ValidationException::withMessages([
                    'items' => 'One or more selected products are invalid.',
                ]);
            }

            $total = $items->sum(function ($item) {
                return (int) $item['quantity'] * (float) $item['price'];
            });

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth('web')->id(),
                'total_price' => $total,
            ]);

            foreach ($items as $productId => $item) {
                $quantity = (int) $item['quantity'];
                $price = (float) $item['price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price,
                ]);

                $products[$productId]->increment('stock', $quantity);
            }

            return $purchase;
        });

        return redirect()
            ->route('admin.purchases.show', $purchase)
            ->with('success', 'Purchase created and stock updated successfully.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load([
            'supplier',
            'user',
            'purchaseItems.product',
        ]);

        return view('admin.purchases.show', compact('purchase'));
    }

    public function invoice(Purchase $purchase)
    {
        $purchase->load([
            'supplier',
            'user',
            'purchaseItems.product',
        ]);

        return view('admin.purchases.invoice', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            foreach ($purchase->purchaseItems as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            $purchase->purchaseItems()->delete();
            $purchase->delete();
        });

        return redirect()
            ->route('admin.purchases.index')
            ->with('success', 'Purchase deleted successfully.');
    }
}
