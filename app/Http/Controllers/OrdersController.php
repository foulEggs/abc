<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Repositories\OrderRepository;
use App\Repositories\OrderSerialRepository;
use App\Repositories\AcceptChannelRepository;
use App\Repositories\PaymentChannelRepository;
use App\Repositories\DistrictRepository;
use App\Validators\OrderValidator;
use App\Criteria\DateSectionCriteria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrdersController extends Controller
{

    /**
     * @var OrderRepository
     */
    protected $repository;

    /**
     * @var OrderValidator
     */
    protected $validator;

    public function __construct(OrderRepository $repository, OrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->repository->pushCriteria(app(DateSectionCriteria::class));

        $fields = ['orders.id', 'orders.sys_order_num', 'orders.user_no', 'orders.total_money', 'orders.create_time', 'orders.trade_channel_name', 'orders.trade_status'];
        $list = $this->repository->orderBy("orders.id", "desc")->paginate($request->limit, $fields);
        foreach($list->getCollection() as $k=>$v){
            $list[$k]->create_time = date('Y-m-d H:i:s', $v->create_time);
            
            $list[$k]->btn = $this->switchState($v->trade_status, 'btn_type');
            $list[$k]->btn_name = $this->switchState($v->trade_status, 'trade_status');
        }
        
        $list_count = $list->total();
        
        echo json_encode(['datas' => $list->getCollection(), 'total'=>$list_count]);
    }

    public function countOrdersByCondition(Request $request)
    {
        $date_search = explode(':', $request->date_search ? : ':,');

        list($start_end, $start_array, $end_array) = $this->getDateSearch($date_search[1], $request->date_type);
        
        $date_format = $start_array[1] == $end_array[1] ? "%Y-%m-%d" : "%Y-%m";//统计粒度，月/日
        
        $fields = "FROM_UNIXTIME(create_time,'".$date_format."') as date,".$request->type."(total_money) as number";

        $count_data =  $this->repository
                            ->select($fields)
                            ->scopeQuery(function($query) use ($start_end){
                                return  $query
                                        ->whereBetween('create_time', [strtotime($start_end[0]),strtotime($start_end[1])])
                                        ->where('trade_status',3);
                            })
                            ->groupBy("date")
                            ->all();
       
        $count_tmp_data = [];
        foreach($count_data as $k=>$v){
            $count_tmp_data[$v->date] = $v;
        }
        
        $results = $this->transformData($start_array, $end_array, $count_tmp_data);

        return json_encode($results);
    }

    protected function getDateSearch($date, $date_type)
    {
        if($date == ','){
            if($date_type == 'by_month'){
                $date = date('Y-01').','.date('Y-m');//没传时间则以当年一月到当前月为准
            } else {
                $date = date('Y-m-01').','.date('Y-m-d');//没传时间则以当月第一天到当前天数为准
            }
        }
       
        $start_end = is_array($date) ? $date : explode(',',$date);

        $start_array = explode('-',$start_end[0]);
        
        $end_array = explode('-',$start_end[1]);
        
        return [$start_end, $start_array, $end_array];
    }

    protected function transformData($start_array, $end_array, $count_tmp_data)
    {
        $result_date = [];
        $result_count = [];

        $start_end = [implode('-', $start_array), implode('-', $end_array)];

        if($start_array[1] == $end_array[1]){
            $date_section = $this->combine_days($start_end[0],$start_end[1]);
            
            $result_date = array_map(function($v){
                return substr($v,8);
            }, $date_section);
                         
            foreach($date_section as $k=>$v){
                    
                 array_push($result_count, isset($count_tmp_data[$v]) ? $count_tmp_data[$v]['number'] : 0);               
            }
        }else{
            $date_section = $this->combine_months(substr($start_end[0],0,7),substr($start_end[1],0,7));
            
            $result_date = array_map(function($v){
                return substr($v,0,7);
            }, $date_section);
         
            foreach($date_section as $k=>$v){
                    
                array_push($result_count, isset($count_tmp_data[$v]) ? $count_tmp_data[$v]['number'] : 0);          
            }
        }

        return ['date'=>$result_date, 'count'=>$result_count];
    }

    public function overallTotal(){
       
        //计算本月的交易信息
        $month_start = strtotime(date('Y-m-01', time()));
        
        $month_end = time();

        $data['month_number'] = number_format($this->getModelByDate($month_start, $month_end)->count());
        
        $data['month_money'] = number_format($this->getModelByDate($month_start, $month_end)->sum('total_money'),2);
        
        
        //计算本周的交易信息
        $week_start = strtotime(date('Y-m-'.(date('w') ? intval(date('d')) - date('w') + 1 : intval(date('d')) - 6)));
        
        $week_end = $week_start + 7*24*3600;
        
        $data['week_number'] = number_format($this->getModelByDate($week_start, $week_end)->count());
        $data['week_money'] = number_format($this->getModelByDate($week_start, $week_end)->sum('total_money'),2);
        
        
        //计算当天的交易信息
        $day_start = strtotime(date('Y-m-d'));
        
        $day_end = time();
        
        $data['day_number'] = number_format($this->getModelByDate($week_start, $week_end)->count());
        $data['day_money'] = number_format($this->getModelByDate($week_start, $week_end)->sum('total_money'),2);
        
        
        //计算交易成功率
        //$success_trade_number = $model->acquireAggregate(['trade_status' => 3], 'count', 'id');
        //$fail_trade_number = $model->acquireAggregate(['trade_status' => 5], 'count', 'id');
        
        //$data['success_rate'] = sprintf("%.2f", $success_trade_number/($success_trade_number + $fail_trade_number) * 100);        
        
        echo json_encode($data);
    }

    protected function getModelByDate($start, $end)
    {
        return $this->repository->scopeQuery(function($query) use ($start, $end){
            return $query->whereBetween("create_time", [$start, $end])->where('trade_status',3);
        });
    }

    public function sortRateByCondition(Request $request)
    {
        $top_num = $request->top_num;

        $date_search = explode(':', $request->date_search ? : ':,');

        list($start_end) = $this->getDateSearch($date_search[1], $request->date_type);
        
        $count_data =  $this->repository
                            ->select('any_value('.$request->name_field.') as name, '.$request->type.'(total_money) as count')
                            ->scopeQuery(function($query) use ($start_end){
                                return  $query
                                        ->whereBetween('create_time', [strtotime($start_end[0]),strtotime($start_end[1])])
                                        ->where('trade_status',3);
                            })
                            ->groupBy($request->group_by_field)
                            ->orderBy('count', 'desc')
                            ->all();

        $topFourName = [];
        $topFourData = [];
        foreach ($count_data as $key => $value) {
            if($value->count > 0){
                $key < $top_num ? array_push($topFourName, $value->name) : ($key == $top_num ? array_push($topFourName, '其它') : true);

                if($key < $top_num){
                    $topFourData[$key] = ['value'=>$value->count, 'name'=>$value->name];
                } else {
                    if(isset($topFourData[$top_num])){
                        $topFourData[$top_num]['value'] = $topFourData[$top_num]['value'] + $value->count;
                    } else {
                        $topFourData[$top_num] = ['value'=>$value->count, 'name'=>'其它'];
                    }
                }
            }
        }
        
        return json_encode(['name'=>$topFourName, 'data'=>$topFourData]); 
    }

   

    /***********************************************other resource*********************************************************/

    public function view(AcceptChannelRepository $AcceptChannelRepository, PaymentChannelRepository $PaymentChannelRepository)
    {
        $data['accept_channel_list'] = $AcceptChannelRepository->all(['sign', 'name']);
        
        $payment_channel_list = $PaymentChannelRepository->all(['key', 'name', 'sign', 'sign_name']);
        
        $data['payment_channel'] = $data['charge_type'] = [];
        
        foreach($payment_channel_list as $k=>$v){
            $data['payment_channel'][$v->key] = $v->name;
            
            $data['charge_type'][$v->key][$v->sign] = $v->sign_name;
        }
        $data['charge_type'] = json_encode($data['charge_type']);
        
        $data['trade_status'] = $this->switchState(null, 'trade_status', true);
        
        if(\Route::current()->uri === "view/trades_detail"){
            return view('trade.trades_detail', $data);
        } elseif (\Route::current()->uri === "view/trades_operation") {
            return view('trade.trades_operation', $data);
        }
    }

    public function countByDistrictView(DistrictRepository $DistrictRepository)
    {
        $district_data = $DistrictRepository->all();
        
        $data = $this->combine_by_level($district_data);

        return view('trade.count_by_district', [
            'city'=>json_encode($data['city']), 
            'district'=>json_encode($data['district']), 
            'team'=>json_encode($data['team'])
        ]);
    }

    public function countByAcceptView(AcceptChannelRepository $AcceptChannelRepository)
    {
        $data['accept_channel_list'] = $AcceptChannelRepository->all(['sign', 'name']);

        return view('trade.count_by_accept', $data);
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

    //组合天为粒度的时间数组
    protected function combine_days($start,$end,$format = "Y-m-d") {
        $start = strtotime($start);
        $end = strtotime($end);
        $days = array();
        for($i=$start;$i<=$end;$i+=24*3600) $days[] = date($format, $i);
        return $days;
    
    }
    
    //组合月为粒度的时间数组
    protected function combine_months($start,$end){
        $timeArr=array();
        $t1=$start;
        $t2=$this->get_months($t1)['1'];
        $timeArr[] = $start;
        
        while($t2<$end || $t1<=$end){
            $t1=date('Y-m-d',strtotime("$t2 +1 day"));
            $t2=$this->get_months($t1)['1'];
            
            $t2=$t2>$end?$end:$t2;
            $timeArr[] = substr($t2,0,7);
        }
        return $timeArr;
    }
    
    protected function get_months($day){//指定月的第一天和最后一天
        $firstday = date('Y-m-01',strtotime($day));
        $lastday = date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        return array($firstday,$lastday);
 
    }
}
