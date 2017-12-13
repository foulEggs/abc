<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ClearRelate extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "clearing_relate";

    protected $fillable = [];

}
