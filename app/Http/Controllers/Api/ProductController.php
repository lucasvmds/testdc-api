<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\Product\StoreRequest;
use App\Http\Requests\Api\Product\UpdateRequest;
use App\Http\Resources\Api\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
    public function index(PaginateRequest $request): JsonResource
    {
        return ProductResource::collection(Product::getAll($request));
    }

    public function store(StoreRequest $request): JsonResource
    {
        $data = $request->validated();
        $product = Product::query()->create($data);
        return ProductResource::make($product);
    }

    public function show(Product $product): JsonResource
    {
        return ProductResource::make($product);
    }

    public function update(UpdateRequest $request, Product $product): JsonResource
    {
        $product->update($request->validated());
        return ProductResource::make($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(status: 204);
    }
}
