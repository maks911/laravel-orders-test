<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{

    protected $table = 'order_products';

    public function getTotalPrice()
    {
        return $this->quantity * $this->price;
    } //
}
