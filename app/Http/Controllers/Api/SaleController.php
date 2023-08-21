<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\Sale\StoreRequest;
use App\Http\Requests\Api\Sale\UpdateRequest;
use App\Http\Resources\Api\SaleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleController extends Controller
{
    public function index(PaginateRequest $request): JsonResource
    {
        return SaleResource::collection(Sale::getAll($request));
    }

    public function store(StoreRequest $request): JsonResource
    {
        $data = $request->collect();
        $sale = Sale::create($data);
        return SaleResource::make($sale);
    }

    public function show(Sale $sale): JsonResource
    {
        return SaleResource::make($sale);
    }

    public function destroy(Sale $sale): JsonResponse
    {
        $sale->delete();
        return response()->json(status: 204);
    }
}
