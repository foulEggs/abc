<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\AcceptChannel;
use App\Client;

class PaymentChannel extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "payment_channel";

    protected $fillable = [
    	"pay_sign",
    	"pay_type",
    	"key",
    	"name",
    	"sign",
    	"sign_name",
    	"clear_money_rate",
    	"discount",
    	"money",
    	"act_type",
    	"apply_type",
    	"status",
    	"money_valve"
    ];

    protected $dateFormat = "U";

    public function accept_channels()
    {
    	return $this->belongsToMany(AcceptChannel::class, "accept_payment");
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, "client_relate", "payment_id", "client_id");
    }

}
