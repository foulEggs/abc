<?php

namespace app\Http\controllers\System;

use App\Http\Controllers\BaseController;
use App\Model\SystemConfig;
use Illuminate\Pagination\LengthAwarePaginator;

class AcceptController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	protected $tablename = "accept_channel";
	
	protected $tablename1 = "districts";
	
	protected $tablename2 = "accept_district";
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'act_type') {
			$arr = [
				'1' => '满减',
				'2' => '满赠',
				'9' => '无活动',
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function accept_channel(){
		$model = new SystemConfig();
		
		$district_data = $model->acquireList($this->tablename1, 1, 10000, ['parent_id'=>['>', 0]], ['id', 'name', 'parent_id', 'level']);
		
		return view('system.accept_channel', ['districts'=>json_encode($district_data)]);
	}
	
	
	public function ajax_list(){
		$model = new SystemConfig();
		
		$where = [];
		
		$list = $model->acquireList($this->tablename, $this->request->input('page', 1), $this->request->input('limit'), $where);
		
		foreach($list as $k=>$v){
			$list[$k]->create_time_str = date('Y-m-d H:i:s', $v->create_time);
			if($v->act_type == 1){
				$list[$k]->act_type_str = "-".$v->money;
			}else if($v->act_type == 2){
				$list[$k]->act_type_str = "+".$v->money;
			}else{
				$list[$k]->act_type_str = "无";
			}
		}
		
		$list_count = $model->acquireListCount($this->tablename, $where);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new SystemConfig();
		
		$id = $this->request->input('id');
		
		$data = $model->acquire($this->tablename, ['id'=>$id]);
		
		$districts = $model->acquireList($this->tablename2, 1, 10000, ['accept_channel_id'=>$id]);
		
		$data->districts = $districts;
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$data['create_time'] = time();
		
		$districts = explode(',', $data['districts_ids']);
		unset($data['districts_ids']);
		
		if($id=$model->add($this->tablename, $data)){
			
			foreach($districts as $k=>$v){
				$model->add($this->tablename2, ['accept_channel_id'=>$id, 'district_id'=>$v]);
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
		
		$districts = explode(',', $data['districts_ids']);
		unset($data['districts_ids']);
		
		if($model->modify($this->tablename, ['id'=>$this->request->input('id')], $data) !== false){
			
			$model->remove($this->tablename2, ['accept_channel_id'=>$this->request->input('id')]);
			
			foreach($districts as $k=>$v){
				$model->add($this->tablename2, ['accept_channel_id'=>$this->request->input('id'), 'district_id'=>$v]);
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
			
			$model->remove($this->tablename2, ['accept_channel_id'=>$this->request->input('id')]);
			
			$result = ['code'=>200, 'msg'=>'删除成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'删除失败'];
		}
		
		echo json_encode($result);
	}
	
}

