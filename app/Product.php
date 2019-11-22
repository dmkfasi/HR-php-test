<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function orders() {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot('price', 'quantity')
            ->withTimestamps();
    }

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'order_products')
            ->withTimestamps();
    }
}
