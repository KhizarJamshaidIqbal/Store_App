<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'highlights',
        'sku',
        'shop_sku',
        'brand',
        'model',
        'texture',
        'color_family',
        'country_of_origin',
        'pack_type',
        'volume',
        'weight',
        'material',
        'features',
        'express_delivery_countries',
        'brand_classification',
        'shelf_life',
        'price',
        'special_price',
        'stock',
        'package_weight',
        'package_length',
        'package_width',
        'package_height',
        'dangerous_goods',
        'is_draft',
        'status'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
