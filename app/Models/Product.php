<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    // The attributes that are mass assignable
    protected $fillable = [
        'product_name',
        'slug',
        'description',
        'product_quantity',
        'price',
        'image',
        'brand',
        'quantity',
        'alert_stock',
    ];

    public function getImageAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


}
