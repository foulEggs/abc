<?php

namespace app\Http\controllers\System;

use App\Http\Controllers\BaseController;
use App\Model\SystemConfig;
use App\Model\Terminal;
use App\Http\service\Qrcode;

class ChargeStaffsController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	protected $tablename = "charge_staffs";
	
	protected $tablename1 = "districts";
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'status') {
			$arr = [
				'1' => '正常',
				'2' => '休假',
				'3' => '离职'
			];
		}
		
		if ($type == 'charge_staff_type') {
			$arr = [
				'1' => '营业厅',
				'2' => '设备'
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function charge_staffs(){
	
		$model = new SystemConfig();
		
		$district_data = $model->acquireList($this->tablename1, 1, 10000, ['parent_id'=>['>', 0]], ['id', 'name', 'parent_id', 'level']);
		
		$data = $this->combine_by_level($district_data);
		
		$terminal_model = new Terminal();
		
		$terminal_data = $terminal_model->acquireList(1,100000,['status'=>1], ['id', 'terminal_key']);
		
		$staff_type_list = $this->switchState(null, 'charge_staff_type', true);
		
		return view('system.charge_staffs', [
			'city'=>json_encode($data['city']), 
			'district'=>json_encode($data['district']), 
			'team'=>json_encode($data['team']), 
			'terminal_list'=>$terminal_data,
			'staff_type_list'=>$staff_type_list
		]);
	}
	
	public function combine_by_level($data){
		
		$result = [];
		
		foreach($data as $k=>$v){
			if($v->level == 2){
				$result['city'][$v->id] = $v;
			}else if($v->level == 3){
				$result['district'][$v->id] = $v;
			}else if($v->level == 4){
				$result['team'][$v->id] = $v;
			}
		}
		
		return $result;
	}
	
	
	public function ajax_list(){
		$model = new SystemConfig();
		
		$where = [];
		
		$list = $model->acquireList($this->tablename, $this->request->input('page', 1), $this->request->input('limit'), $where);
		
		foreach($list as $k=>$v){
			$list[$k]->charge_staff_type_str = $this->switchState($v->charge_staff_type, 'charge_staff_type');
			$list[$k]->create_time_str = date('Y-m-d H:i:s', $v->create_time);			
		}
		
		$list_count = $model->acquireListCount($this->tablename, $where);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new SystemConfig();
		
		$id = $this->request->input('id');
		
		$data = $model->acquire($this->tablename, ['id'=>$id]);
		
		unset($data->pwd);
		
		$data = json_decode(json_encode($data), true);
		
		$terminal_model = new Terminal();
		
		$terminal_id = $terminal_model->acquireTerminalStaffList(['charge_staff_id'=>$id]);
		
		$data['terminal_id'] = $terminal_id[0]->terminal_id;
		
		$data['qrcode'] = config('custom.charge_staffs_qrcode_path').md5($id).".png";
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$pwd = $data['pwd'] ? : 'scgd123456';
		
		$data['pwd'] = md5('charge_staffs'.$pwd);
		
		$data['create_time'] = time();
		
		$terminal_id = $data['terminal_id'];
		unset($data['terminal_id']);
		
		$model->startTransaction();
		
		if($id=$model->add($this->tablename, $data)){
			
			$img_url = config('custom.charge_staffs_qrcode_path').md5($id).".png";
			
			$url = "http://www.baidu.com";
		
			QRcode::png($url,$img_url,'H',5);
			
			$terminal_model = new Terminal();
			
			if($terminal_model->addTerminalStaff(['terminal_id'=>$terminal_id, 'charge_staff_id'=>$id], false)){
				$model->commit();
			}else{
				$model->rollBack();
			}
			
			$result = ['code'=>200, 'msg'=>'添加成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'添加失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_edit(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token','id');

		if(empty($data['pwd'])){
			unset($data['pwd']);
		}else{
			$data['pwd'] = md5('charge_staffs'.$data['pwd']);
		}
		
		$terminal_id = $data['terminal_id'];
		unset($data['terminal_id']);
		
		$model->startTransaction();
		
		if($model->modify($this->tablename, ['id'=>$this->request->input('id')], $data) !== false){
			
			$terminal_model = new Terminal();
			
			$remove_result = $terminal_model->removeTerminalStaff(['charge_staff_id'=>$this->request->input('id')]);
			
			if($terminal_model->addTerminalStaff(['terminal_id'=>$terminal_id, 'charge_staff_id'=>$this->request->input('id')], false) && $remove_result !== false){
				$model->commit();
			}else{
				$model->rollBack();
			}
			
			$result = ['code'=>200, 'msg'=>'修改成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'修改失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_remove(){
		$model = new SystemConfig();
		
		$model->startTransaction();
		
		if($model->remove($this->tablename, ['id'=>$this->request->input('id')]) !== false){
			
			$terminal_model = new Terminal();
			
			if($terminal_model->removeTerminalStaff(['charge_staff_id'=>$this->request->input('id')]) !== false){
				$model->commit();
			}else{
				$model->rollBack();
			}
			
			$result = ['code'=>200, 'msg'=>'删除成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'删除失败'];
		}
		
		echo json_encode($result);
	}
	
}

