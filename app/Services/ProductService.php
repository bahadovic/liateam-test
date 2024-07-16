<?php

namespace App\Services;

use App\Facades\JWT;

use App\Http\Resources\ProductResource;
use App\Http\Resources\RefreshTokenResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductService
{
    public function index(): array
    {
        $products = Product::all();
        return responseFormatter()->success(data: ProductResource::make($products));
    }

    public function store(array $params): array
    {
        Product::create($params);
        return responseFormatter()->success(message: 'product.store.successful');
    }

    public function show(array $params): array
    {
        $product = Product::filter($params);
        return responseFormatter()->success(data: ProductResource::make($product));
    }

    public function update(array $params)
    {
        $product = Product::filter($params);
        $product->update($params);

        return responseFormatter()->success( data: ProductResource::make($product),message: 'product.update.successful');
    }

    public function destroy(array $params)
    {
        $product = Product::filter($params);
        $product->delete();

        return responseFormatter()->success( message: 'product.delete.successful');
    }
}
