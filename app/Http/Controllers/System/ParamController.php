<?php

namespace app\Http\controllers\System;

use App\Http\Controllers\BaseController;
use App\Model\SystemConfig;

class ParamController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	protected $tablename = "sys_param";
	
	protected $tablename1 = "accept_channel";
	
	protected $tablename2 = "districts";
	
	protected $tablename3 = "payment_channel";
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'status') {
			$arr = [
				'1' => '正常',
				'2' => '禁用',
			];
		}
		
		if ($type == 'btn_type') {
			$arr = [
				'1' => 'btn-success',
				'2' => 'btn-danger',				
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function sys_param(){
		$model = new SystemConfig();
		
		$accept_channel_data = $model->acquireList($this->tablename1, 1, 10000, [], ['id', 'name']);
		
		$payment_channel_data = $model->acquireList($this->tablename3, 1, 10000, [], ['id', 'name']);
		
		$district_data = $model->acquireList($this->tablename2, 1, 10000, [], ['id', 'name', 'parent_id', 'level']);
		
		return view('system.sys_param', [
			'districts'=>json_encode($district_data),
			'accept_channel' => $accept_channel_data,
			'payment_channel' => $payment_channel_data
		]);
	}
	
	
	public function ajax_list(){
		$model = new SystemConfig();
		
		$where = [];
		
		$join = [
			'accept_channel' =>  ['sys_param.accept_channel', 'accept_channel.id'],
			'payment_channel' => ['sys_param.payment_channel', 'payment_channel.id']
		];
		
		$fields = ['sys_param.id', 'sys_param.key', 'sys_param.name', 'sys_param.value', 'sys_param.status', 'sys_param.districts_ids', 'sys_param.BOSS_account', 'sys_param.delay_time', 'accept_channel.name as accept_channel_name', 'payment_channel.name as payment_channel_name'];
		
		$list = $model->acquireListJoin($this->tablename, $this->request->input('page', 1), $this->request->input('limit'), $where, $join, $fields);
		
		foreach($list as $k=>$v){
			$list[$k]->delay_time_str = date('Y-m-d', $v->delay_time);
			
			if(strpos(','.$v->districts_ids.',',',1,') !== false){
				$list[$k]->districts_ids = "ALL";
			}else{
				$list[$k]->districts_ids = "--";
			}
			
			$list[$k]->accept_channel_name = $v->accept_channel_name !== null ? : "ALL";
			$list[$k]->payment_channel_name = $v->payment_channel_name !== null ? : "ALL";			
			
			$list[$k]->btn = $this->switchState($v->status, 'btn_type');
			$list[$k]->btn_name = $this->switchState($v->status, 'status');
		}
		
		$list_count = $model->acquireListCount($this->tablename, $where);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new SystemConfig();
		
		$id = $this->request->input('id');
		
		$data = $model->acquire($this->tablename, ['id'=>$id]);
		
		//$district_ids = explode(',', $data->districts_ids);
		
		$data->districts = empty($data->districts_ids) ? [] : explode(',', $data->districts_ids);
		
		$data->delay_time = date('Y-m-d');
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$data['delay_time'] = strtotime($data['delay_time']);
		
		if($model->add($this->tablename, $data)){
			
			$result = ['code'=>200, 'msg'=>'添加成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'添加失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_edit(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token','id');
		
		$data['delay_time'] = strtotime($data['delay_time']);
		
		if($model->modify($this->tablename, ['id'=>$this->request->input('id')], $data) !== false){
			
			$result = ['code'=>200, 'msg'=>'修改成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'修改失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_remove(){
		$model = new SystemConfig();
		
		if($model->remove($this->tablename, ['id'=>$this->request->input('id')]) !== false){
			
			$result = ['code'=>200, 'msg'=>'删除成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'删除失败'];
		}
		
		echo json_encode($result);
	}
	
}

