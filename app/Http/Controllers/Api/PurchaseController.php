<?php

namespace App\Http\Controllers\Api;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends BaseApiController
{
    public function index()
    {
        try {

            $purchases = Purchase::with([
                'supplier',
                'user',
                'purchaseItems.product'
            ])->latest()->get();

            return $this->success(
                $purchases,
                'Purchases Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function show(Purchase $purchase)
    {
        try {

            $purchase->load([
                'supplier',
                'user',
                'purchaseItems.product'
            ]);

            return $this->success(
                $purchase,
                'Purchase Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'total_price' => 'required|numeric|min:0',
                'items'       => 'required|array|min:1',

                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity'   => 'required|integer|min:1',
                'items.*.price'      => 'required|numeric|min:0',
            ]);

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id'     => auth()->id(),
                'total_price' => $request->total_price,
            ]);

            foreach ($request->items as $item) {

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'subtotal'    => $item['quantity'] * $item['price'],
                ]);

                Product::where(
                    'id',
                    $item['product_id']
                )->increment(
                    'stock',
                    $item['quantity']
                );
            }

            DB::commit();

            return $this->success(
                $purchase->load([
                    'supplier',
                    'user',
                    'purchaseItems.product'
                ]),
                'Purchase Created Successfully',
                201
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();

        try {

            $purchase->load('purchaseItems');

            foreach ($purchase->purchaseItems as $item) {

                Product::where(
                    'id',
                    $item->product_id
                )->decrement(
                    'stock',
                    $item->quantity
                );
            }

            $purchase->purchaseItems()->delete();

            $purchase->delete();

            DB::commit();

            return $this->success(
                null,
                'Purchase Deleted Successfully'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }
}