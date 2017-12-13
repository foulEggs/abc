<?php
namespace app\Http\controllers\Trade;
date_default_timezone_set('Asia/Shanghai');
use App\Http\Controllers\BaseController;
use App\Model\Trade;
use App\Model\SystemConfig;
use Illuminate\Pagination\LengthAwarePaginator;

class TradeController extends BaseController{
	
	/* 访问权限标记 */
	protected $visit_auth_flag = true;
	
	private function switchState ($state, $type, $get=false) {
		$arr = [];
		
		if ($type == 'trade_status') {
			$arr = [
				'1' => '未支付',
				'2' => '支付中',
				'3' => '成功',
				'4' => '退款',
				'5' => '失败'
			];
		}
		
		if ($type == 'btn_type') {
			$arr = [
				'1' => 'btn-primary',
				'2' => 'btn-primary',
				'3' => 'btn-success',
				'4' => 'btn-primary',
				'5' => 'btn-danger'				
			];
		}
		
		if ($get === true) {
			return $arr;
		}
		
		return isset($arr[$state])?$arr[$state]:$state;
		
	}
	
	public function trades_detail(){
		
		$sys_model = new SystemConfig();
		
		$data = [];
		
		$data['accept_channel_list'] = $sys_model->acquireList('accept_channel', 1, 1000, [], ['sign', 'name']);
		
		$payment_channel_list = $sys_model->acquireList('payment_channel', 1, 1000, [], ['key', 'name', 'sign', 'sign_name']);
		
		$data['payment_channel'] = $data['charge_type'] = [];
		
		foreach($payment_channel_list as $k=>$v){
			$data['payment_channel'][$v->key] = $v->name;
			
			$data['charge_type'][$v->key][$v->sign] = $v->sign_name;
		}
		$data['charge_type'] = json_encode($data['charge_type']);
		
		$data['trade_status'] = $this->switchState(null, 'trade_status', true);
		
		//$paginator = new LengthAwarePaginator($list, $list_count, 1, null, ['path'=>'/admin/trades_detail']);
		
		return view('trade.trades_detail', $data);
	}
	
	public function trades_operation(){
		
		$sys_model = new SystemConfig();
		
		$data = [];
		
		$data['accept_channel_list'] = $sys_model->acquireList('accept_channel', 1, 1000, [], ['sign', 'name']);
		
		$payment_channel_list = $sys_model->acquireList('payment_channel', 1, 1000, [], ['key', 'name', 'sign', 'sign_name']);
		
		$data['payment_channel'] = $data['charge_type'] = [];
		
		foreach($payment_channel_list as $k=>$v){
			$data['payment_channel'][$v->key] = $v->name;
			
			$data['charge_type'][$v->key][$v->sign] = $v->sign_name;
		}
		$data['charge_type'] = json_encode($data['charge_type']);
		
		return view('trade.trades_operation', $data);
	}
	
	public function ajax_list(){
		$model = new Trade();
		
		$where = [];
		
		$page = $this->request->input('page', 1);
		
		$limit = $this->request->input('limit', 1);
		
		$filter = $this->request->input('filter', []);
		
		$orders = $orders_serial = false;
		
		foreach($filter as $k=>$v){
			if($v){
				$where[$k] = $v;
				
				if(in_array($k, ['sys_order_num', 'user_no', 'trade_channel', 'orders.trade_status'])){
					$orders = true;
				}
				
				if(in_array($k, ['payment_channel', 'charge_type'])){
					$orders_serial = true;
				}
			}
		}
		
		$time_filter = $this->request->input('time_filter', []);
		
		if($time_filter['start_time'] || $time_filter['end_time']){
			if($time_filter['start_time'] && $time_filter['end_time']){
				if($time_filter['start_time'] != $time_filter['end_time']){
					$where['orders.create_time'] = ['between',[strtotime($time_filter['start_time']), strtotime($time_filter['end_time'])]];
				}else{
					$where['orders.create_time'] = ['between',[strtotime($time_filter['start_time']), strtotime($time_filter['end_time'])+3600]];
				}
			}else if($time_filter['start_time'] && !$time_filter['end_time']){
				$where['orders.create_time'] = ['>', $time_filter['start_time']];
			}else if(!$time_filter['start_time'] && $time_filter['end_time']){
				$where['orders.create_time'] = ['<', $time_filter['end_time']];
			}
			
			$orders = true;
		}
		
		$fields = ['orders.id', 'orders.sys_order_num', 'orders.user_no', 'orders.total_money', 'orders.create_time', 'orders.trade_channel_name', 'orders.trade_status'];
			
		
		if($orders && $orders_serial){
			//print_r($where);
			
			$join = ['orders_serial' => ['orders.sys_order_num','orders_serial.to_sys_order_num']];
			
			$list = $model->acquireListJoin($page, $limit, $where, $join, $fields, 'orders.id');
			
			$list_count = $model->acquireListJoinCount($where, $join);
		} else if (($orders && !$orders_serial) || (!$orders && !$orders_serial)) {
			
			$list = $model->acquireList($page, $limit, $where, $fields);
			
			$list_count = $model->acquireListCount($where);
			
		} else if (!$orders && $orders_serial) {
			$serial_fileds = ['to_sys_order_num'];
			
			$serial_list = $model->acquireSerialList($page, $limit, $where, $serial_fileds, [], true);
			
			$list = $model->acquireList(1, $limit, ['sys_order_num' => ['in',array_column($serial_list, 'to_sys_order_num')]], $fields);
			
			$list_count = $model->acquireSerialListCount($where);
		}
		
		foreach($list as $k=>$v){
			$list[$k]->create_time = date('Y-m-d H:i:s', $v->create_time);
			
			$list[$k]->btn = $this->switchState($v->trade_status, 'btn_type');
			$list[$k]->btn_name = $this->switchState($v->trade_status, 'trade_status');
		}
		
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_operation_list(){
		$model = new Trade();
		
		$where = [];
		
		$page = $this->request->input('page', 1);
		
		$limit = $this->request->input('limit', 1);
		
		$filter = $this->request->input('filter', []);
		
		foreach($filter as $k=>$v){
			if($v){
				$where[$k] = $v;
			}else{
				if($k == "trade_status"){
					$where[$k] = ['in', [3,5]];
				}
			}
		}
		
		$time_filter = $this->request->input('time_filter', []);
		
		if($time_filter['start_time'] && $time_filter['end_time']){
			if($time_filter['start_time'] != $time_filter['end_time']){
				$where['orders.create_time'] = ['between',[strtotime($time_filter['start_time']), strtotime($time_filter['end_time'])]];
			}else{
				$where['orders.create_time'] = ['between',[strtotime($time_filter['start_time']), strtotime($time_filter['end_time'])+3600]];
			}
		}else if($time_filter['start_time'] && !$time_filter['end_time']){
			$where['orders.create_time'] = ['>', $time_filter['start_time']];
		}else if(!$time_filter['start_time'] && $time_filter['end_time']){
			$where['orders.create_time'] = ['<', $time_filter['end_time']];
		}
		//print_r($where);
		$fields = ['orders.id', 'orders.sys_order_num', 'orders.user_no', 'orders.total_money', 'orders.create_time', 'orders.trade_channel_name', 'orders.trade_status'];
		
		$join = ['orders_serial' => ['orders.sys_order_num','orders_serial.to_sys_order_num']];
		
		$list = $model->acquireListJoin($page, $limit, $where, $join, $fields);
		
		foreach($list as $k=>$v){
			$list[$k]->create_time = date('Y-m-d H:i:s', $v->create_time);
			
			$list[$k]->btn = $this->switchState($v->trade_status, 'btn_type');
			$list[$k]->btn_name = $this->switchState($v->trade_status, 'trade_status');
		}
		
		$list_count = $model->acquireListJoinCount($where, $join);
		
		echo json_encode(['datas' => $list, 'total'=>$list_count]);
	}
	
	public function ajax_data(){
		$model = new Trade();
		
		$id = $this->request->input('id');
		
		$data = $model->acquireSerialList(1, 1000, ['to_sys_order_num'=>$id], ['serial_num', 'charge_money', 'charge_time', 'payment_channel_name', 'charge_type_name', 'trade_status']);
		
		foreach($data as $k=>$v){
			$data[$k]->charge_time = empty($v->charge_time) ? '-' : date('Y-m-d H:i:s');
			
			$data[$k]->btn = $this->switchState($v->trade_status, 'btn_type');
			$data[$k]->btn_name = $this->switchState($v->trade_status, 'trade_status');
		}
		
		echo json_encode($data);
	}
	
}

