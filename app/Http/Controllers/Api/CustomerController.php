<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\Customer\StoreRequest;
use App\Http\Requests\Api\Customer\UpdateRequest;
use App\Http\Resources\Api\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerController extends Controller
{
    public function index(PaginateRequest $request): JsonResource
    {
        return CustomerResource::collection(Customer::getAll($request));
    }

    public function store(StoreRequest $request): JsonResource
    {
        $data = $request->validated();
        $customer = Customer::query()->create($data);
        return CustomerResource::make($customer);
    }

    public function show(Customer $customer): JsonResource
    {
        return CustomerResource::make($customer);
    }

    public function update(UpdateRequest $request, Customer $customer): JsonResource
    {
        $customer->update($request->validated());
        return CustomerResource::make($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json(status: 204);
    }
}
