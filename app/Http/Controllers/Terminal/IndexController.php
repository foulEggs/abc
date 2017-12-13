<?php

namespace app\Http\controllers\Terminal;

use App\Http\Controllers\BaseController;
use App\Model\Terminal;

class IndexController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'status') {
			$arr = [
				'1' => '正常',
				'2' => '维修',
				'3' => '遗失',
				'4' => '异常',
				'5' => '收回'
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function index(){
		$model = new Terminal();
		
		$data['status_list'] = $this->switchState(null, 'status', true);
		
		return view('terminal.index', $data);
	}
	
	
	public function ajax_list(){
		$model = new Terminal();
		
		$where = [];
		
		$filter = $this->request->input('filter', []);
		
		foreach($filter as $k=>$v){
			if($v){
				$where[$k] = $v;
			}
		}
		
		$list = $model->acquireList($this->request->input('page', 1), $this->request->input('limit'), $where);
		
		foreach($list as $k=>$v){
			$list[$k]->delivery_time_str = date('Y-m-d', $v->delivery_time);
			
			$list[$k]->status_str = $this->switchState($v->status, 'status');
		}
		
		$list_count = $model->acquireListCount($where);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new Terminal();
		
		$id = $this->request->input('id');
		
		$data = $model->acquire(['id'=>$id]);
		
		$data->delivery_time = date('Y-m-d', $data->delivery_time);
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new Terminal();
		
		$data = $this->request->except('_token');
		
		$data['delivery_time'] = strtotime($data['delivery_time']);
		
		if($id=$model->add($data)){
			
			$result = ['code'=>200, 'msg'=>'添加成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'添加失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_edit(){
		$model = new Terminal();
		
		$data = $this->request->except('_token');
		
		$data['delivery_time'] = strtotime($data['delivery_time']);

		if($model->modify(['id'=>$this->request->input('id')], $data) !== false){
			
			$result = ['code'=>200, 'msg'=>'修改成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'修改失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_remove(){
		$model = new Terminal();
		
		if($model->remove(['id'=>$this->request->input('id')]) !== false){
			
			$result = ['code'=>200, 'msg'=>'删除成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'删除失败'];
		}
		
		echo json_encode($result);
	}
	
}

