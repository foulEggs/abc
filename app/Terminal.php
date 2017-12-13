<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\ChargeStaff;

class Terminal extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "terminal";

    protected $fillable = [
    	"terminal_key",
    	"cash_pledge",
    	"delivery_time",
    	"status"
    ];

    protected $dateFormat = "U";

    public function charge_staffs()
    {
    	return $this->belongsToMany(ChargeStaff::class, "terminal_staff", "terminal_id", "charge_staff_id");
    }
}
