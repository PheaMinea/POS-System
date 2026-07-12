<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'shop_email',
        'shop_phone',
        'shop_address',
        'currency_symbol',
        'currency_position',
        'vat_percentage',
        'shop_logo',
    ];
}
