<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    private $_line_item_amount = [0];

    protected $fillable = [
        'client_email',
        'partner_id',
        'status'
    ];

    public const orderStatus = [
        0 => 'новый',
        10 => 'подтвержден',
        20 => 'завершен'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot('price', 'quantity')
            ->withTimestamps();
    }

    public function partner() {
        return $this->belongsTo(Partner::class);
    }

    public function getStatusName() {
        return self::orderStatus[$this->status];
    }

    public function getStatusList() {
      return self::orderStatus;
    }

    public function getTotalAmount() {
        $this->products->each(function ($p) {
            $this->setLineItemAmount($p->pivot->price * $p->pivot->quantity);
        });

        $total = collect($this->_line_item_amount);

        return $total->sum();
    }    

    public function setLineItemAmount($amount = 0) {
        $this->_line_item_amount[] = $amount;
    }
}