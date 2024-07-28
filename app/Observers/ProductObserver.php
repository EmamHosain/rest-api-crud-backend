<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
       
    }
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        $id = auth()->user()->id ?? User::first()->id;
        $product->created_by = $id;
       
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }
    public function updating(Product $product): void
    {
        $id = auth()->user()->id ?? User::first()->id;
        $product->updated_by = $id;
    }
    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
