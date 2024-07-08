<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasSlug;

    // Specify the table associated with the model
    protected $table = 'categories';

    // The attributes that are mass assignable
    protected $fillable = [
        'categoryName',
        'slug',
        'categoryImg',
    ];
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('categoryName')
            ->saveSlugsTo('slug');
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }





}
