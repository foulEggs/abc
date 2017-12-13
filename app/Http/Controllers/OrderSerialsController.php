<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OrderSerialCreateRequest;
use App\Http\Requests\OrderSerialUpdateRequest;
use App\Repositories\OrderSerialRepository;
use App\Repositories\PaymentChannelRepository;
use App\Validators\OrderSerialValidator;


class OrderSerialsController extends Controller
{

    /**
     * @var OrderSerialRepository
     */
    protected $repository;

    /**
     * @var OrderSerialValidator
     */
    protected $validator;

    public function __construct(OrderSerialRepository $repository, OrderSerialValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function test(){
        $data = $this->repository->get();

        dd($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $fields = ['serial_num', 'charge_money', 'charge_time', 'payment_channel_name', 'charge_type_name', 'trade_status'];

        $data = $this->repository->all($fields);
        
        foreach($data as $k=>$v){
            $data[$k]->charge_time = empty($v->charge_time) ? '-' : date('Y-m-d H:i:s');
            
            $data[$k]->btn = $this->switchState($v->trade_status, 'btn_type');
            $data[$k]->btn_name = $this->switchState($v->trade_status, 'trade_status');
        }
        
        echo json_encode($data);
    }

    public function countOrderserialByCondition(Request $request)
    {
        //$this->repository->pushCriteria(app(DateSectionCriteria::class));

        $date_search = explode(':', $request->date_search ? : ':,');

        list($start_end, $start_array, $end_array) = $this->getDateSearch($date_search[1], $request->date_type);
        
        $date_format = $start_array[1] == $end_array[1] ? "%Y-%m-%d" : "%Y-%m";//统计粒度，月/日
        
        $fields = "FROM_UNIXTIME(charge_time,'".$date_format."') as date,".$request->type."(charge_money) as number";

        $count_data  =  $this->repository
                            ->select($fields)
                            ->scopeQuery(function($query) use ($start_end){
                                return  $query
                                        ->whereBetween('charge_time', [strtotime($start_end[0]),strtotime($start_end[1])])
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

    public function sortRateByCondition(Request $request)
    {
        $top_num = $request->top_num;

        $date_search = explode(':', $request->date_search ? : ':,');

        list($start_end) = $this->getDateSearch($date_search[1], $request->date_type);
        
        $count_data  = $this->repository
                            ->select('any_value('.$request->name_field.') as name, '.$request->type.'(charge_money) as count')
                            ->scopeQuery(function($query) use ($start_end){
                                return  $query
                                        ->whereBetween('charge_time', [strtotime($start_end[0]),strtotime($start_end[1])])
                                        ->where('trade_status',3);
                            })
                            ->groupBy($request->group_by_field)
                            ->orderBy('count', 'desc')
                            ->all();

        $topFourName = [];
        $topFourData = [];
        foreach ($count_data as $key => $value) {
            if($value->count){
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

    public function countByPaymentView(PaymentChannelRepository $PaymentChannelRepository)
    {
        $data['payment_channel'] = $PaymentChannelRepository->all(['key', 'name']);

        return view('trade.count_by_payment', $data);
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
