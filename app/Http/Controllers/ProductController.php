<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{

    public function __construct(
        private readonly ProductService $service
    )
    {
    }
    public function index()
    {
        $data = $this->service->index();
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function store(ProductStoreRequest $request)
    {
        $data = $this->service->store(params: $request->safe()->toArray());
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'inventory' => 'sometimes|integer',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validatedData);

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
