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
        $data = $this->service->show(params: ['id' => $id]);
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $data = $this->service->update(params: $request->safe()->merge(['id' => $id])->toArray());
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function destroy($id)
    {
        $data = $this->service->destroy(params: ['id' => $id]);
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }
}
