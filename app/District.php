<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\AcceptChannel;

class District extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
    	"parent_id",
    	"level",
    	"name",
    	"clear_money_rate",
    	"status"
    ];

    protected $dateFormat = 'U';

    public function accept_channels()
    {
    	return $this->belongsToMany(AcceptChannel::class, "accept_district");
    }

}
