<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // This to accumulate line item total amount and get Order's total at the end
    private $_line_item_amount = [0];

    protected $fillable = [
        'client_email',
        'partner_id',
        'status'
    ];

    // As the task describes Order's status set, got to be hardcoded
    protected const orderStatus = [
        0 => 'новый',
        10 => 'подтвержден',
        20 => 'завершен'
    ];
    
    // To use within the code for orderStatus
    public const STATE_NEW = 0;
    public const STATE_CONFIRMED = 10;
    public const STATE_COMPLETED = 20;

    // Laravel Eloquent bindings
    public function products() {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot('price', 'quantity')
            ->withTimestamps();
    }

    // Laravel Eloquent bindings
    public function partner() {
        return $this->belongsTo(Partner::class);
    }

    // Text description order status getter
    public function getStatusName() {
        return self::orderStatus[$this->status];
    }

    // Order status set getter
    public function getStatusList() {
      return self::orderStatus;
    }

    // Calculates Order's total by computing line item amount
    public function getTotalAmount() {
        $this->products->each(function ($p) {
            $this->setLineItemAmount($p->pivot->price * $p->pivot->quantity);
        });

        $total = collect($this->_line_item_amount);

        return $total->sum();
    }    

    // Line item amount setter
    public function setLineItemAmount($amount = 0) {
        $this->_line_item_amount[] = $amount;
    }
}