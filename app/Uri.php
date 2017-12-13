<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Repository;

class Uri extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = false;

    protected $fillable = [
    	'name',
    	'uri',
    	'method',
    	'repository_id',
    	'status'
    ];

    public function repository(){
    	return $this->belongsTo(Repository::class);
    }

}
