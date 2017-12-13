<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\ClearingRelate;

class Clearing extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "clearing";

    protected $fillable = [];

    public function clearing_relates()
    {
    	return $this->hasMany(ClearingRelate::class, 'to_clearing_id', 'id');
    }

}
