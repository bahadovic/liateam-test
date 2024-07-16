<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $service
    )
    {
    }
    public function index()
    {
        $data = $this->service->index();
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);

    }

    public function store(OrderStoreRequest $request)
    {
        $data = $this->service->store(params: $request->safe()->toArray());
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function show($id)
    {
        $data = $this->service->show(params: ['id' => $id]);
        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function update(OrderUpdateRequest $request, $id)
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
