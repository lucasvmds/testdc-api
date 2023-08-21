<?php

namespace App\Http\Resources\Api;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Sale */
        $sale = $this;
        return [
            'id' => $sale->id,
            'customer_id' => $sale->customer_id,
            'user_id' => $sale->user_id,
            'total' => $sale->total,
            'customer' => $sale->customer?->name,
            'user' => $sale->user->name,
            'updated_at' => $sale->updated_at,
            'created_at' => $sale->created_at,
            $this->mergeWhen(
                $request->routeIs('sales.index'),
                [
                    'products_count' => $sale->products()->count(),
                    'installments_count' => $sale->installments()->count(),
                ],
            ),
            $this->mergeWhen(
                $request->routeIs('sales.show', 'sales.store'),
                [
                    'products' => $this->getProducts(),
                    'installments' => $sale->installments,
                ],
            ),
        ];
    }

    private function getProducts(): Collection
    {
        /** @var Sale */
        $sale = $this;
        return $sale
                    ->products()
                    ->get()
                    ->map(fn(Product $product): array =>
                        [
                            'id' => $product->id,
                            'name' => $product->name,
                            'value' => $product->pivot->value,
                            'quantity' => $product->pivot->quantity,
                            'total' => $product->pivot->total,
                        ]
                    );
    }
}
