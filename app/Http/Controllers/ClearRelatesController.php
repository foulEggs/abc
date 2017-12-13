<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\ClearRelateRepository;
use App\Repositories\DistrictRepository;
use App\Criteria\DateSectionCriteria;
use App\Repositories\ChargeStaffRepository;

class ClearRelatesController extends Controller
{

    /**
     * @var ClearRelateRepository
     */
    protected $repository;

    /**
     * @var ClearRelateValidator
     */
    protected $validator;

    public function __construct(ClearRelateRepository $repository)
    {
        $this->repository = $repository;       
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clearRelates = $this->repository->all();

        return $clearRelates;
    }

    public function allIndex(Request $request)
    {
        $this->repository->pushCriteria(app(DateSectionCriteria::class));

        $date_format = $request->date_type == 'day' ? "%Y-%m-%d" : "%Y-%m";//统计粒度，月/日

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


            $this->repository = $this->repository->scopeQuery(function($query) use ($clear_relate_id){
                return $query->where('clear_relate_id', $clear_relate_id);
            });
        }
        
        $fields = "FROM_UNIXTIME(charge_time,'".$date_format."') as date,any_value(clear_type) as clear_type,sum(clear_money) as clear_money";

        $clear_all_data =  $this->repository->select($fields)->groupBy("date")->all();

        return $this->transformData($clear_all_data);
    }

    public function allView(DistrictRepository $DistrictRepository, ChargeStaffRepository $ChargeStaffRepository)
    {
        $district_data = $DistrictRepository->all();
        
        $data = $this->combine_by_level($district_data);

        $salesman_data = $ChargeStaffRepository->scopeQuery(function($query){
            return $query->where('charge_staff_type', 1);
        })->all(['id', 'username']);

        return view('clearing.clearing_all', [
            'city'=>json_encode($data['city']), 
            'district'=>json_encode($data['district']), 
            'team'=>json_encode($data['team']),
            'salesman'=>$salesman_data,
            'clear_type'=>$this->switchState(null, 'clear_type', true)
        ]);
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

    protected function transformData($data)
    {
        foreach($data as $k=>$v){
            $data[$k]->clear_type_name = $this->switchState($v->clear_type, 'clear_type');
        }

        return $data;
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
}
