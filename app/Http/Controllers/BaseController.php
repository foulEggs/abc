<?php

namespace app\Http\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller {
	
	protected $request;
	
	protected $switch_array = [];
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	/* 跳过权限的方法 */
	protected $pass_auth_action = [];
	
	/* 当前菜单对象 */
	protected $current_menu = [];
	
	/* 当前菜单方法对象 */
	protected $current_menu_action = [];
	
	/* 当前用户对象 */
	protected $current_user = [];
	
	public function __construct (Request $request) {
		//parent::__construct();
		
		$this->request = $request;
		
		$this->openssl_cnf = getcwd().'/openssl.cnf';
		
		$this->current_user = $this->request->session()->get('user');
		
		
		if ($this->visit_auth_flag === true) {
			
			// if(!$this->verifyLogin()){
			// 	echo 400;
			// 	exit;
			// }
		}
	}
	
	public function test(){
		//$a = encrypt('login123');
		//echo decrypt($a);
	}
	
	/* 验证登录 */
	protected function verifyLogin () {
		
		if ($this->request->session()->has('user')) {
			return true;
		}
		return false;
	}
	
	/* 参数验证 */
	protected function paramsVerify ($params, $type) {
		foreach ($params as $key => $val) {
			if (!$this->request->has($key, $type) || ($val === true && empty($this->request->$type($key)))) {
				return false;
			}
		}
		return true;
	}

	/* 转换字段的字意 */
	protected function converFieldToStr ($datas, $conver) {
		foreach ($conver as $key => $val) {
			foreach ($datas as $key2 => $val2) {
				if (isset($val[$val2[$key]])) {
					$datas[$key2][$key.'_str'] = $val[$val2[$key]];
				}
			}
		}
		
		return $datas;
	}
	
	public function _empty()
	{
		header("HTTP/1.1 404 Not Found");exit;  
	}

}

