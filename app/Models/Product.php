<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, HasSlug;
    protected $table = 'products';

    // The attributes that are mass assignable
    protected $fillable = [
        'title',
        'slug',
        'short_des',
        'product_quantity',
        'price',
        'image',
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



}
