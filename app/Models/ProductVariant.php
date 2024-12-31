<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'price',
        'special_price',
        'stock',
        'seller_sku',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
