<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\District;
use App\PaymentChannel;

class AcceptChannel extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "accept_channel";

    protected $fillable = [
    	"name",
    	"sign",
    	"clear_money_rate",
    	"discount",
    	"money",
    	"act_type",
    	"apply_type",
    	"status",
    	"money_valve"
    ];

    protected $dateFormat = 'U';

    public function districts()
    {
    	return $this->belongsToMany(District::class, 'accept_district');
    }

    public function payment_channels()
    {
    	return $this->belongsToMany(PaymentChannel::class, 'accept_payment');
    }
}
