<?php

namespace app\Http\controllers\System;

use App\Http\Controllers\BaseController;
use App\Model\SystemConfig;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	protected $tablename = "payment_channel";
	
	protected $tablename1 = "accept_payment";
	
	protected $tablename2 = "accept_channel";
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'act_type') {
			$arr = [
				'1' => '满减',
				'2' => '满赠',
				'9' => '无活动'
			];
		}
		
		if ($type == 'pay_sign') {
			$arr = [
				'1' => '微信',
				'2' => '支付宝',
				'3' => '银联支付',
				'4' => '现金',
				'5' => '京东支付',
				'5' => 'QQ钱包',
				'6' => '百度钱包',
				'7' => '银行支付',
				'8' => '实物卡',
				'1001' => '扫呗(支付宝)',
				'1002' => '扫呗(微信)',
				'1003' => '扫呗(银联卡支付)',
				'1004' => '扫呗(支付宝扫码)',
				'1005' => '扫呗(微信扫码)',
				'9999' => '超级码'
			];
		}
		
		if ($type == 'pay_type') {
			$arr = [
				'0' => '无',
				'1' => '二维码支付',
				'2' => '网关支付',
				'3' => '刷卡支付',
				'4' => '扫码支付',
				'5' => '服务号内支付',
				'6' => '掌上支付',
				'7' => 'APP内支付',
				'8' => '签约代收/代扣',
				'9' => '社区银行',
				'10' => 'ATM/CRS',
				'11' => '柜台'
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function payment_channel(){
		$model = new SystemConfig();
		
		$accept_data = $model->acquireList($this->tablename2, 1, 10000, ['status'=>1]);
		
		$data['accept_list'] = $accept_data;
		
		$data['pay_sign_list'] = $this->switchState(null, 'pay_sign', true);
		$data['pay_type_list'] = $this->switchState(null, 'pay_type', true);
		
		return view('system.payment_channel', $data);
	}
	
	
	public function ajax_list(){
		$model = new SystemConfig();
		
		$where = [];
		
		$list = $model->acquireList($this->tablename, $this->request->input('page', 1), $this->request->input('limit'), $where);
		
		foreach($list as $k=>$v){
			//$list[$k]->create_time_str = date('Y-m-d H:i:s', $v->create_time);
			$list[$k]->pay_sign_str = $this->switchState($v->pay_sign, 'pay_sign');
			$list[$k]->pay_type_str = $this->switchState($v->pay_type, 'pay_type');
		}
		
		$list_count = $model->acquireListCount($this->tablename, $where);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new SystemConfig();
		
		$id = $this->request->input('id');
		
		$data = $model->acquire($this->tablename, ['id'=>$id]);
		
		$accept = $model->acquireList($this->tablename1, 1, 10000, ['payment_channel_id'=>$id]);
		
		$data->accept = $accept;
		
		echo json_encode($data);
	}
	
	public function ajax_add(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$data['create_time'] = time();
		
		$accept_ids = [];
		
		if(!empty($data['accept_id'])){
			$accept_ids = $data['accept_id'];
		}
		unset($data['accept_id']);
		
		if($id=$model->add($this->tablename, $data)){
			
			if(!empty($accept_ids)){
				foreach($accept_ids as $k=>$v){
					$model->add($this->tablename1, ['payment_channel_id'=>$v, 'accept_channel_id'=>$id]);
				}
			}
			
			$result = ['code'=>200, 'msg'=>'添加成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'添加失败'];
		}
		
		echo json_encode($result);
	}
	
	public function ajax_edit(){
		$model = new SystemConfig();
		
		$data = $this->request->except('_token');
		
		$accept_ids = [];
		
		if(!empty($data['accept_id'])){
			$accept_ids = $data['accept_id'];
		}
		unset($data['accept_id']);
		
		if($model->modify($this->tablename, ['id'=>$this->request->input('id')], $data) !== false){
			
			$model->remove($this->tablename1, ['payment_channel_id'=>$this->request->input('id')]);
			
			if(!empty($accept_ids)){
				foreach($accept_ids as $k=>$v){
					$model->add($this->tablename1, ['payment_channel_id'=>$this->request->input('id'), 'accept_channel_id'=>$v]);
				}
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
			
			$model->remove($this->tablename1, ['payment_channel_id'=>$this->request->input('id')]);
			
			$result = ['code'=>200, 'msg'=>'删除成功'];
		}else{
			$result = ['code'=>500, 'msg'=>'删除失败'];
		}
		
		echo json_encode($result);
	}
	
}

