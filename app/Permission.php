<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Menu;
use App\Uri;

class Permission extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = false;

    protected $fillable = [
    	'menu_id',
    	'name',
    	'gate_label',
    	'status'
    ];

    public function menu(){
    	return $this->belongsTo(Menu::class);
    }

    public function uris(){
    	return $this->belongsToMany(Uri::class);
    }

    public function roles(){
    	return $this->belongsToMany(Uri::class);
    }

}
