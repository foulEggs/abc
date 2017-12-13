<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ChargeStaffCreateRequest;
use App\Http\Requests\ChargeStaffUpdateRequest;
use App\Repositories\ChargeStaffRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\TerminalRepository;
use App\Validators\ChargeStaffValidator;


class ChargeStaffsController extends Controller
{

    /**
     * @var ChargeStaffRepository
     */
    protected $repository;

    /**
     * @var ChargeStaffValidator
     */
    protected $validator;

    public function __construct(ChargeStaffRepository $repository, ChargeStaffValidator $validator)
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
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $chargeStaffs = $this->repository->paginate($request->limit);

        return $this->transform($chargeStaffs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ChargeStaffCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ChargeStaffCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $data = $request->all();

            $data['pwd'] = md5($data['pwd'] ? : "scgd123456");

            $chargeStaff = $this->repository->create($data);

            $chargeStaff->terminals()->attach([$request->terminal_id]);

            $response = [
                'message' => 'ChargeStaff created.',
                'data'    => $chargeStaff->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chargeStaff = $this->repository->with("terminals")->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $chargeStaff,
            ]);
        }

        return view('chargeStaffs.show', compact('chargeStaff'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $chargeStaff = $this->repository->find($id);

        return view('chargeStaffs.edit', compact('chargeStaff'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ChargeStaffUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ChargeStaffUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $data = $request->all();

            if(empty($data['pwd'])){
                unset($data['pwd']);
            }else{
                $data['pwd'] = md5($data['pwd']);
            }
            $chargeStaff = $this->repository->update($data, $id);

            $chargeStaff->terminals()->sync([$request->terminal_id]);

            $response = [
                'message' => 'ChargeStaff updated.',
                'data'    => $chargeStaff->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->repository->find($id);

        $data->terminals()->detach();

        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'ChargeStaff deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ChargeStaff deleted.');
    }

    /*******************************************other resource******************************************************/

    public function view(DistrictRepository $DistrictRepository, TerminalRepository $TerminalRepository)
    {
        $district_data = $DistrictRepository->all();
        
        $data = $this->combine_by_level($district_data);
        
        $terminal_data = $TerminalRepository->scopeQuery(function($query){return $query->where(['status'=>1]);})->all();
        
        $staff_type_list = $this->switchState(null, 'charge_staff_type', true);
        
        return view('system.charge_staffs', [
            'city'=>json_encode($data['city']), 
            'district'=>json_encode($data['district']), 
            'team'=>json_encode($data['team']), 
            'terminal_list'=>$terminal_data,
            'staff_type_list'=>$staff_type_list
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

    private function switchState ($state, $type, $get=false) {
        $arr = [];
        
        if ($type == 'status') {
            $arr = [
                '1' => '正常',
                '2' => '休假',
                '3' => '离职'
            ];
        }
        
        if ($type == 'charge_staff_type') {
            $arr = [
                '1' => '营业厅',
                '2' => '设备'
            ];
        }
        
        if ($get === true) {
            return $arr;
        }
        
        return isset($arr[$state])?$arr[$state]:$state;
        
    }

    protected function transform($paginate)
    {
        $paginate->getCollection()->each(function($item, $key){
            $item->created_at_str = $item->created_at->format('Y-m-d H:i:s');
        });

        return $paginate;
    }
}
