<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'image'
    ];

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        if (str_starts_with($this->image, '/storage/')) {
            return asset(ltrim($this->image, '/'));
        }

        if (Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . ltrim($this->image, '/'));
        }

        $productPath = 'products/' . $this->image;

        if (Storage::disk('public')->exists($productPath)) {
            return asset('storage/' . $productPath);
        }

        if (str_contains($this->image, '/')) {
            return asset('storage/' . ltrim($this->image, '/'));
        }

        return asset('storage/products/' . $this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
