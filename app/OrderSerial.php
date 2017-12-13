<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Order;

class OrderSerial extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "orders_serial";

    protected $fillable = [];

    public function order()
    {
    	return $this->belongsTo(Order::class, 'to_sys_order_num', 'sys_order_num');
    }

}
