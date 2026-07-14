<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'reference_no',
        'status',
        'telegram_sent_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'telegram_sent_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'id'
        );
    }
}