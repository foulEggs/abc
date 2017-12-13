<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class DistrictValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
        	"parent_id" => "numeric",
        	"level" => "required|numeric",
        	"name" => "required",
        	"clear_money_rate" => "max:100|min:0",
        	"status" => "required|numeric"
        ],
        ValidatorInterface::RULE_UPDATE => [
        	"parent_id" => "required|numeric",
        	"level" => "required|numeric",
        	"name" => "required",
        	"clear_money_rate" => "max:100|min:0",
        	"status" => "required|numeric"
        ],
   ];
}
