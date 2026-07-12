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

        if (Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        $productPath = 'products/' . $this->image;

        if (Storage::disk('public')->exists($productPath)) {
            return Storage::url($productPath);
        }

        return null;
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
