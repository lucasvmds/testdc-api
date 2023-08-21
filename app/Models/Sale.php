<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Requests\Api\PaginateRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'user_id',
        'customer_id',
    ];

    protected $casts = [
        'total' => 'float',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['value', 'quantity']);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public static function getAll(PaginateRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->orderBy('created_at', 'desc')
                            ->paginate($request->validated('items', 20));
    }

    public static function create(Collection $data): static
    {
        return DB::transaction(function() use($data): static {
            $sale_data = $data->only(['total', 'customer_id'])->toArray();
            $sale_data['user_id'] = User::current()?->id;
            /** @var static */
            $sale = static::query()->create($sale_data);
            foreach ($data->get('products', []) as $product) {
                $sale->products()->attach($product['id'], [
                    'value' => $product['value'],
                    'quantity' => $product['quantity'],
                ]);
            }
            if (count($data->get('installments', [])) > 0) {
                $sale->installments()->createMany($data->get('installments'));
            }
            return $sale;
        });
    }
}
