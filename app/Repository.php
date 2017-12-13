<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Uri;

class Repository extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = false;

    protected $fillable = [
    	'name',
    	'model'
    ];

    protected function uris(){
    	return $this->hasMany(Uri::class);
    }

}
