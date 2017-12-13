<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Menu extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = false;

    protected $fillable = [
    	'parent_id',
    	'name',
    	'icon',
    	'extra',
    	'status',
    	'level',
    	'order'
    ];

}
