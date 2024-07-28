<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use HasFactory, HasSlug;
    protected $table = 'products';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_des',
        'product_quantity',
        'price',
        'image',
        'updated_by',
        'created_by'
    ];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }


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
