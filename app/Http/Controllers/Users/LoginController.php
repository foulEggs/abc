<?php

namespace app\Http\controllers\Users;

use App\Http\Controllers\BaseController;
use App\Model\UserAdmin;

class LoginController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = false;
	
	public function login(){
		return view('login.index');
	}
	
	//登录
	public function do_login(){
		$data = $this->request->except('_token');
		
		$model = new UserAdmin();
		
		$where = ['username'=>$data['username'], 'password'=>md5('login'.$data['username'].'_'.$data['passwd'])];
		
		$user = $model->acquire($where);
		
		if(!empty($user)){
			
			$this->request->session()->put('user', $user);
			
			$result = [
				'code' => 200,
				'msg' => "登录成功"
			];
		}else{
			$result = [
				'code' => 401,
				'msg' => "用户不存在或密码不正确"
			];
		}
		
		echo json_encode($result);
	}
	
	//登出
	public function do_logout(){
		$this->request->session()->forget('user');
		
		return redirect()->route('login');
	}
}

