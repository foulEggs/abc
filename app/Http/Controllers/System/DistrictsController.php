<?php

namespace app\Http\controllers\System;

use App\Http\Controllers\BaseController;
use App\Model\SystemConfig;

class DistrictsController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	protected $tablename = "districts";
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'act_type') {
			$arr = [
				'1' => '满减',
				'2' => '满赠',
			];
		}
		
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
	
	public function districts(){
		
		return view('system.districts');
	}
	
	
	public function ajax_list(SystemConfig $model){
		//$model = new SystemConfig();
		
		$where = ['parent_id'=>$this->request->input('parent_id', 0)];
		
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
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$data['create_time'] = time();
		
		if($id=$model->add($this->tablename, $data)>0){
			$result = ['code'=>200, 'msg'=>'添加成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'添加失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_edit(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token','id');
		
		if($model->modify($this->tablename, ['id'=>$this->request->input('id')], $data) !== false){
			$result = ['code'=>200, 'msg'=>'修改成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'修改失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_remove(){
		$model = new SystemConfig();
		
		if($model->acquireListCount($this->tablename, ['parent_id'=>$this->request->input('id')]) === 0){
			if($model->remove($this->tablename, ['id'=>$this->request->input('id')]) !== false){
				$result = ['code'=>200, 'msg'=>'删除成功'];
			}else{
				$result = ['code'=>500, 'msg'=>'删除失败'];
			}
		}else{
			$result = ['code'=>401, 'msg'=>'该区域下有子区域无法删除'];
		}
		
		echo json_encode($result);
	}
	
}

