<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\terminal;

class ChargeStaff extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "charge_staffs";

    protected $fillable = [
    	"name",
    	"terminal_key",
    	"username",
    	"pwd",
    	"city",
    	"city_name",
    	"district",
    	"district_name",
    	"team",
    	"team_name",
    	"status",
    	"clear_money_rate",
    	"charge_staff_type"
    ];

    protected $dateFormat = "U";

    public function terminals()
    {
    	return $this->belongsToMany(Terminal::class, 'terminal_staff', "charge_staff_id", "terminal_id");
    }

}
