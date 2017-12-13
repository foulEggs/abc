<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class OrderSerial extends Model implements Transformable
{
    use TransformableTrait;

    /**
	* 关联到模型的数据表
	*
	* @var string
	*/
	protected $table = 'orders_serial';

    protected $fillable = [];

    public function order()
    {
    	return $this->belongsTo('App\Models\Order', 'sys_order_num', 'to_sys_order_num');
    }

}
