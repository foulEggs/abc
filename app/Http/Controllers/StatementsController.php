<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\AccountCheckRepository;
use App\Repositories\DistrictRepository;
use App\Criteria\DateSectionCriteria;
use App\Repositories\ChargeStaffRepository;
use App\Repositories\ClearRelateRepository;

use Excel;

class StatementsController extends Controller
{
    public function tradeStatement(OrderRepository $OrderRepository, Request $request)
    {
    	$OrderRepository->pushCriteria(app(DateSectionCriteria::class));

        $date_format = $request->date_type == 'day' ? "%Y-%m-%d" : "%Y-%m";//统计粒度，月/日

        $fields = "FROM_UNIXTIME(create_time,'".$date_format."') as date,count(*) as total_number, sum(total_money) as total_money";

        $cell_data =  $OrderRepository->select($fields)->scopeQuery(function ($query) {
        	return $query->where('trade_status', 3);
        })->groupBy("date")->all();

        if($request->action == "preview") {
       		return $cell_data;
        } else {
        	$title = "交易统计（".$request->get('start-create_time')."至".$request->get('end-create_time')."）";

        	Excel::create($title,function($excel) use ($cell_data, $title){
        		
	            $excel->sheet($title, function($sheet) use ($cell_data){
	                $sheet->fromModel($cell_data, null, 'A1', false, false);

	                $sheet->prependRow(1, ['日期', '交易笔数', '交易金额（元）']);
	            });
	        })->export('xls');
        }
    }

    public function accountStatement(AccountCheckRepository $AccountCheckRepository, Request $request)
    {
    	$AccountCheckRepository->pushCriteria(app(DateSectionCriteria::class));

    	
    	if($request->date_type == 'month') {
    		$date_format = "%Y-%m";//统计粒度，月/日

	        $fields = "FROM_UNIXTIME(create_time,'".$date_format."') as date,sum(correct_sum) as correct_sum, sum(correct_money) as correct_money";

	        $statement =  $AccountCheckRepository->select($fields)->groupBy("date")->all();
	    } else {
	    	$statement = $AccountCheckRepository->all(['create_time', 'correct_sum', 'correct_money']);
	    	
	    	foreach($statement as $k=>$v){
	    		$statement[$k]->date = date('Y-m-d', $v->create_time);
	    	}
	    }

        if($request->action == "preview") {
       		return $statement;
        } else {
        	$title = "对账统计（".$request->get('start-create_time')."至".$request->get('end-create_time')."）";

        	Excel::create($title,function($excel) use ($statement, $title){
        		
	            $excel->sheet($title, function($sheet) use ($statement){
	                $sheet->fromModel($statement, null, 'A1', false, false);

	                $sheet->prependRow(1, ['日期', '对账笔数', '对账金额（元）']);
	            });
	        })->export('xls');
        }
    }

    public function clearingStatement(ClearRelateRepository $ClearRelateRepository, Request $request)
    {
    	$ClearRelateRepository->pushCriteria(app(DateSectionCriteria::class));

        $date_format = $request->date_type == 'day' ? "%Y-%m-%d" : "%Y-%m";//统计粒度，月/日

        //$clear_type_str = $this->switchState($request->clear_type, 'clear_type');

        //结算方为区域或收费员时需附加条件
        if(in_array($request->clear_type, ['district', 'salesman'])) {
            if($request->clear_type == "district") {
                if(!empty($request->team)) {
                    $clear_relate_id = $request->team;
                } elseif (!empty($request->district)) {
                    $clear_relate_id = $request->district;
                } elseif (!empty($request->city)) {
                    $clear_relate_id = $request->city;
                }
            } elseif ($request->clear_type == "salesman") {
                $clear_relate_id = $request->salesman;
            }


            $ClearRelateRepository = $ClearRelateRepository->scopeQuery(function($query) use ($clear_relate_id){
                return $query->where('clear_relate_id', $clear_relate_id);
            });
        }
        
        $fields = "FROM_UNIXTIME(charge_time,'".$date_format."') as date,any_value(clear_relate_name) as clear_name, count(*) as clear_count, sum(clear_money) as clear_money";

        $statement =  $ClearRelateRepository->select($fields)->groupBy("date")->all();

        if($request->action == "preview") {
       		return $statement;
        } else {
        	$title = "结算统计（结算方：[".$statement[0]->clear_name."]".$request->get('start-charge_time')."至".$request->get('end-charge_time')."）";
        	
        	Excel::create($title,function($excel) use ($statement){
        		
	            $excel->sheet("结算统计", function($sheet) use ($statement){
	                $sheet->fromModel($statement, null, 'A1', false, false);

	                $sheet->prependRow(1, ['日期', '结算方', '对账笔数', '对账金额（元）']);
	            });
	        })->export('xls');
        }
    }

    private function switchState ($state, $type, $get=false) {
        $arr = [];
        
        if ($type == 'clear_type') {
            $arr = [
                'payment' => '支付平台',
                'sc_company' => '省公司',
                'district' => '区域',
                'salesman' => '收费员',               
            ];
        }
        
        if ($get === true) {
            return $arr;
        }
        
        return isset($arr[$state])?$arr[$state]:$state;
        
    }

    protected function combine_by_level($data){
        
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

    public function tradeView()
    {
    	return view('statement.trade_statement');
    }

    public function accountView()
    {
    	return view('statement.account_statement');
    }

    public function clearingView(DistrictRepository $DistrictRepository, ChargeStaffRepository $ChargeStaffRepository)
    {
        $district_data = $DistrictRepository->all();
        
        $data = $this->combine_by_level($district_data);

        $salesman_data = $ChargeStaffRepository->scopeQuery(function($query){
            return $query->where('charge_staff_type', 1);
        })->all(['id', 'username']);

        return view('statement.clearing_statement', [
            'city'=>json_encode($data['city']), 
            'district'=>json_encode($data['district']), 
            'team'=>json_encode($data['team']),
            'salesman'=>$salesman_data,
            'clear_type'=>$this->switchState(null, 'clear_type', true)
        ]);
    }

}
