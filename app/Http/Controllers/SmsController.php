<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Service\SmsService;

class SmsController extends Controller
{
    public function sendSms()
    {
    	$response = SmsService::sendSms(
		    "阿里云短信测试专用", // 短信签名
		    "SMS_110145157", // 短信模板编号
		    "13378114046", // 短信接收者
		    Array(  // 短信模板中字段的值
		        "code"=>"12345"
		    )
		);
		dd($response);
    }
}  
