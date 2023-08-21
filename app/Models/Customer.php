<?php

namespace App\Models;

use App\Http\Requests\Api\PaginateRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    protected $casts = [
        'phone' => 'integer',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public static function getAll(PaginateRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->orderBy('name')
                            ->paginate($request->validated('items', 20));
    }
}
