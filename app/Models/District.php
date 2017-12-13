<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

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

}
