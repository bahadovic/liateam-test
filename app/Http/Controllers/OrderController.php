<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return Order::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $products = [];

        foreach ($validatedData['products'] as $product) {
            $productModel = Product::findOrFail($product['id']);
            if ($productModel->inventory < $product['quantity']) {
                return response()->json(['error' => 'Insufficient inventory for product ID ' . $product['id']], 400);
            }

            $productModel->inventory -= $product['quantity'];
            $productModel->save();

            $totalPrice += $productModel->price * $product['quantity'];
            $products[] = $product;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'products' => $products,
            'total_price' => $totalPrice,
        ]);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        return Order::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Update logic here (similar to store)
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(null, 204);
    }
}
