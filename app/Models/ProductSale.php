<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSale extends Pivot
{
    //
    protected $fillable = [
        'value',
        'quantity',
    ];
}
