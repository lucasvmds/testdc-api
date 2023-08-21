<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\Product\SearchRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
    ];

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class)->withPivot(['value', 'quantity', 'total']);
    }

    public static function getAll(PaginateRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->orderBy('name')
                            ->paginate($request->validated('items', 20));
    }

    public function deleteIfNotInUse(): bool
    {
        $sale = $this->sales()->first(['sales.id']);
        if ($sale) return false;
        $this->delete();
        return true;
    }

    public static function search(SearchRequest $request): Collection
    {
        return static::query()
                            ->orderBy('name')
                            ->when(
                                $request->validated('search'),
                                fn(Builder $builder, string $search): Builder => $builder->where('name', 'LIKE', "%$search%"),
                            )
                            ->get();
    }
}
