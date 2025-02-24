<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SpecialOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'discount_amount',
        'discount_percentage',
        'image',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'terms_conditions'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
        'applicable_products' => 'array',
        'terms_conditions' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {
            if (empty($offer->slug)) {
                $offer->slug = Str::slug($offer->title);
            }
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'special_offer_products');
    }

    public function getIsActiveAttribute()
    {
        $now = now();
        return $this->status === 'active' &&
               $this->start_date <= $now &&
               $this->end_date >= $now;
    }
}
