<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,

            'category_id'   => $this->category_id,
            'category_name' => $this->category?->name,

            'name'          => $this->name,
            'price'         => $this->price,
            'stock'         => $this->stock,

            'image'         => $this->image,

            'image_url'     => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}