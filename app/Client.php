<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\PaymentChannel;

class Client extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'client_class';

    protected $fillable = [
    	"name",
    	"key",
    	"type",
    	"version",
    	"version_tiny",
    	"screen",
    	"facility",
    	"status"
    ];

    protected $dateFormat = 'U';

    public function payment_channels()
    {
        return $this->belongsToMany(PaymentChannel::class, "client_relate", "client_id", "payment_id");
    }
}
