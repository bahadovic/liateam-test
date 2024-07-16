<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;

class OrderService
{
    public function index(): array
    {
        $orders = Order::all();
        return responseFormatter()->success(data: ProductResource::make($orders));
    }

    public function store(array $params): array
    {
        $totalPrice = 0;
        $products = [];

        foreach ($params['products'] as $product) {
            $product = Product::filter($product['id']);
            if (!$product){
                return responseFormatter()->entity(error: ['productId' => 'order.product.not_font']);
            }
            if ($product->inventory < $product['quantity']) {
                return responseFormatter()->entity(error: 'Insufficient inventory for product ID ' . $product['id']);
            }

            $product->inventory -= $product['quantity'];
            $product->save();

            $totalPrice += $product->price * $product['quantity'];
            $products[] = $product;
        }

        Order::create([
            'user_id' => auth()->id(),
            'products' => $products,
            'total_price' => $totalPrice,
        ]);

        return responseFormatter()->success(message: 'order.store.successful');
    }

    public function show(array $params): array
    {
        $order = Order::filter($params);
        return responseFormatter()->success(data: OrderResource::make($order));
    }

    public function update(array $params)
    {

        $order = Order::filter($params);
        if (!$order){
            return responseFormatter()->entity(error: ['orderId' => 'order.not_font']);
        }

        $totalPrice = 0;
        $products = [];

        foreach ($params['products'] as $product) {
            $product = Product::filter($product['id']);
            if (!$product){
                return responseFormatter()->entity(error: ['productId' => 'order.product.not_font']);
            }
            if ($product->inventory < $product['quantity']) {
                return responseFormatter()->entity(error: 'Insufficient inventory for product ID ' . $product['id']);
            }

            $product->inventory -= $product['quantity'];
            $product->save();

            $totalPrice += $product->price * $product['quantity'];
            $products[] = $product;
        }


        $order->update([
            'user_id' => auth()->id(),
            'products' => $products,
            'total_price' => $totalPrice,
        ]);

        return responseFormatter()->success(data: OrderResource::make($order),message: 'order.update.successful');
    }

    public function destroy(array $params)
    {
        $product = Order::filter($params);
        $product->delete();

        return responseFormatter()->success( message: 'order.delete.successful');
    }
}
