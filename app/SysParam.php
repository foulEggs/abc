<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\AcceptChannel;
use App\PaymentChannel;

class SysParam extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "sys_param";

    protected $fillable = [
    	"key",
    	"name",
    	"value",
    	"accept_channel",
    	"payment_channel",
    	"districts_ids",
    	"BOSS_account",
    	"delay_time",
    	"status"
    ];

    protected $dateFormat = "U";

    public function accept_channels()
    {
    	return $this->belongsTo(AcceptChannel::class, "accept_channel");
    }

    public function payment_channels()
    {
    	return $this->belongsTo(PaymentChannel::class, "payment_channel");
    }

}
