<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
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
        'status',
        'image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'weight' => 'float',
        'package_weight' => 'float',
        'package_length' => 'float',
        'package_width' => 'float',
        'package_height' => 'float',
        'dangerous_goods' => 'boolean',
        'is_draft' => 'boolean',
        'express_delivery_countries' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'stock' => 'integer'
    ];

    /**
     * The attributes that should be appended to arrays.
     *
     * @var array<string>
     */
    protected $appends = ['primary_image_url', 'discount_percentage'];

    /**
     * Get the product's primary image URL.
     *
     * @return string|null
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        return $primaryImage ? asset('storage/' . $primaryImage->image_path) : null;
    }

    /**
     * Calculate the discount percentage if special price is set.
     *
     * @return float|null
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        if ($this->special_price && $this->price > 0) {
            return round((($this->price - $this->special_price) / $this->price) * 100, 2);
        }
        return null;
    }

    /**
     * Get the product's images.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the product's variants.
     *
     * @return HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_draft', false);
    }

    /**
     * Scope a query to only include draft products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('is_draft', true);
    }

    /**
     * Check if the product is on sale.
     *
     * @return bool
     */
    public function isOnSale(): bool
    {
        return $this->special_price !== null && $this->special_price < $this->price;
    }

    /**
     * Check if the product is in stock.
     *
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
