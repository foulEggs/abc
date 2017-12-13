<?php

namespace app\Http\controllers\System;

use App\Http\Controllers\BaseController;
use App\Model\SystemConfig;

class ClientController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	protected $tablename = "client_class";
	
	protected $tablename1 = "payment_channel";
	
	protected $tablename2 = "client_relate";
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'status') {
			$arr = [
				'1' => '正常',
				'2' => '禁用',
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function client(){
		$model = new SystemConfig();
		//支付渠道
		$data['payment_channel'] = $model->acquireList($this->tablename1, 1, 1000, ['status'=>1], ['id', 'name']);
		
		return view('system.client', $data);
	}
	
	
	public function ajax_list(){
		$model = new SystemConfig();
		
		$where = [];
		
		$list = $model->acquireList($this->tablename, $this->request->input('page', 1), $this->request->input('limit'), $where);
		
		foreach($list as $k=>$v){
			$list[$k]->create_time_str = date('Y-m-d H:i:s', $v->create_time);
			$list[$k]->status_str = $this->switchState($v->status, 'status');
		}
		
		$list_count = $model->acquireListCount($this->tablename, $where);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new SystemConfig();
		
		$id = $this->request->input('id');
		
		$data = $model->acquire($this->tablename, ['id'=>$id]);
		
		$payment = $model->acquireList($this->tablename2, 1, 1000, ['client_id'=>$id], ['payment_id']);
		
		$data->payment = $payment;
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$data['create_time'] = time();
		
		$payment_relate_data = $data['payment'];
		unset($data['payment']);
		
		if($id=$model->add($this->tablename, $data)){
			
			$relate_data = [];
			
			foreach($payment_relate_data as $k=>$v){
				$relate_data[] = ['client_id'=>$id, 'payment_id'=>$v];				
			}
			
			$model->add($this->tablename2, $relate_data, false);
			
			$result = ['code'=>200, 'msg'=>'添加成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'添加失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_edit(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token','id');
		
		$payment_relate_data = $data['payment'];
		unset($data['payment']);
		
		if($model->modify($this->tablename, ['id'=>$this->request->input('id')], $data) !== false){
			
			$model->remove($this->tablename2, ['client_id'=>$this->request->input('id')]);
			
			foreach($payment_relate_data as $k=>$v){
				$model->add($this->tablename2, ['client_id'=>$this->request->input('id'), 'payment_id'=>$v]);
			}
			
			$result = ['code'=>200, 'msg'=>'修改成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'修改失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_remove(){
		$model = new SystemConfig();
		
		if($model->remove($this->tablename, ['id'=>$this->request->input('id')]) !== false){
			
			$model->remove($this->tablename2, ['client_id'=>$this->request->input('id')]);
			
			$result = ['code'=>200, 'msg'=>'删除成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'删除失败'];
		}
		
		echo json_encode($result);
	}
	
}

