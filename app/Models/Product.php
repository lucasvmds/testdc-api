<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Requests\Api\PaginateRequest;
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

    protected $casts = [
        'value' => 'float',
    ];

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class)->withPivot(['value', 'quantity']);
    }

    public static function getAll(PaginateRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->orderBy('name')
                            ->paginate($request->validated('items', 20));
    }
}
