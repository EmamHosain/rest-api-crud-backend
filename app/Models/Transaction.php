<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'order_id',
        'paid_amount',
        'balance',
        'payment_method',
        'transaction_date',
        'transaction_amount',
    ];


    protected function casts(): array
    {
        return [
            'transaction_date' => 'date'
        ];
    }
}
