<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class AccountCheck extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "account_check";

    protected $fillable = [];

}
