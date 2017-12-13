<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Order extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];

    public function orderSerial()
    {
    	return $this->hasMany(OrderSerial::class, 'to_sys_order_num', 'sys_order_num');
    }
}
