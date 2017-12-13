<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class User extends Model implements Transformable,AuthenticatableContract
{
    use TransformableTrait,Authenticatable;

    protected $fillable = [
    	"username",
    	"password",
    	"type",
    	'state',
    	'is_del'
    ];

}
