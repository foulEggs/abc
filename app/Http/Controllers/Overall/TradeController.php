<?php

namespace app\Http\controllers\Overall;
date_default_timezone_set('Asia/Shanghai');
use App\Http\Controllers\BaseController;
use App\Order;
use App\Model\Trade;
use App\Model\SystemConfig;
use Illuminate\Http\Request;

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
	
	public function overview_trades(){
		
		return view('ovreall.trades_detail', $data);
	}
	
	
	public function ajax_list(){
		$model = new Trade();
		
		$where = [];
		
		$page = 1;
		
		$limit = $this->request->input('limit');
		
		
		$fields = ['orders.id', 'orders.sys_order_num', 'orders.user_no', 'orders.total_money', 'orders.create_time', 'orders.trade_channel_name', 'orders.trade_status'];
		
		$join = ['orders_serial' => ['orders.sys_order_num','orders_serial.to_sys_order_num']];
		
		$list = $model->acquireListJoin($page, $limit, $where, $join, $fields);
		
		foreach($list as $k=>$v){
			$list[$k]->create_time = date('Y-m-d H:i:s', $v->create_time);
			
			$list[$k]->btn = $this->switchState($v->trade_status, 'btn_type');
			$list[$k]->btn_name = $this->switchState($v->trade_status, 'trade_status');
		}
		
		$list_count = $model->acquireListJoinCount($where, $join);
		
		echo json_encode(['datas' => $list, 'total'=>10]);
	}
	
	public function ajax_total(){
		$model = new Trade();
		
		//计算本月的交易信息
		$month_start = strtotime(date('Y-m-01', time()));
		
		$month_end = time();
		
		$month_where = ['create_time' => ['between', [$month_start, $month_end]], 'trade_status' => 3];
		
		$data['month_number'] = number_format($model->acquireAggregate($month_where, 'count', 'id'));
		$data['month_money'] = number_format($model->acquireAggregate($month_where, 'sum', 'total_money'),2);
		
		
		//计算本周的交易信息
		$week_start = strtotime(date('Y-m-'.(date('w') ? intval(date('d')) - date('w') + 1 : intval(date('d')) - 6)));
		
		$week_end = $week_start + 7*24*3600;
		
		$week_where = ['create_time' => ['between', [$week_start, $week_end]], 'trade_status' => 3];
		
		$data['week_number'] = number_format($model->acquireAggregate($week_where, 'count', 'id'));
		$data['week_money'] = number_format($model->acquireAggregate($week_where, 'sum', 'total_money'),2);
		
		
		//计算当天的交易信息
		$day_start = strtotime(date('Y-m-d'));
		
		$day_end = time();
		
		$day_where = ['create_time' => ['between', [$day_start, $day_end]], 'trade_status' => 3];
		
		$data['day_number'] = number_format($model->acquireAggregate($day_where, 'count', 'id'));
		$data['day_money'] = number_format($model->acquireAggregate($day_where, 'sum', 'total_money'),2);
		
		
		//计算交易成功率
		//$success_trade_number = $model->acquireAggregate(['trade_status' => 3], 'count', 'id');
		//$fail_trade_number = $model->acquireAggregate(['trade_status' => 5], 'count', 'id');
		
		//$data['success_rate'] = sprintf("%.2f", $success_trade_number/($success_trade_number + $fail_trade_number) * 100);		
		
		echo json_encode($data);
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

	public function ajax_accept_total (Request $request) {
		$date_section = [
			strtotime($request->start ? : date('Y-01')),
			strtotime($request->end ? : date('Y-m'))
		];

		$list = Order::whereBetween("create_time",$date_section)->where("trade_status",1)->take(5)->get();
		dd($list);
	}

	
}

