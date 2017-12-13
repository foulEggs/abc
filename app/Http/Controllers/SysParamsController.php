<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SysParamCreateRequest;
use App\Http\Requests\SysParamUpdateRequest;
use App\Repositories\SysParamRepository;
use App\Repositories\AcceptChannelRepository;
use App\Repositories\PaymentChannelRepository;
use App\Repositories\DistrictRepository;
use App\Validators\SysParamValidator;


class SysParamsController extends Controller
{

    /**
     * @var SysParamRepository
     */
    protected $repository;

    /**
     * @var SysParamValidator
     */
    protected $validator;

    public function __construct(SysParamRepository $repository, SysParamValidator $validator)
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
        $sysParams = $this->repository->with("accept_channels", "payment_channels")->paginate($request->limit);
        
        return $this->transform($sysParams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SysParamCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SysParamCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $data = $request->all();
            $data['delay_time'] = strtotime($data['delay_time']);
            $sysParam = $this->repository->create($data);

            $response = [
                'message' => 'SysParam created.',
                'data'    => $sysParam->toArray(),
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
        $sysParam = $this->repository->find($id);
        $sysParam->delay_time = date('Y-m-d', $sysParam->delay_time);
        $sysParam->districts = empty($sysParam->districts_ids) ? [] : explode(',', $sysParam->districts_ids);
        if (request()->wantsJson()) {

            return response()->json([
                'data' => $sysParam,
            ]);
        }

        return view('sysParams.show', compact('sysParam'));
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

        $sysParam = $this->repository->find($id);

        return view('sysParams.edit', compact('sysParam'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  SysParamUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(SysParamUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $data = $request->all();
            $data['delay_time'] = strtotime($data['delay_time']);
            $sysParam = $this->repository->update($data, $id);

            $response = [
                'message' => 'SysParam updated.',
                'data'    => $sysParam->toArray(),
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
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'SysParam deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'SysParam deleted.');
    }

    /*******************************************other resource******************************************************/

    public function view(AcceptChannelRepository $AcceptChannelRepository, PaymentChannelRepository $PaymentChannelRepository, DistrictRepository $DistrictRepository)
    {
        $accept_channel_data = $AcceptChannelRepository->all(['id','name']);
        
        $payment_channel_data = $PaymentChannelRepository->all(['id','name']);
        
        $district_data = $DistrictRepository->all(['id', 'name', 'parent_id', 'level']);
        
        return view('system.sys_param', [
            'districts'=>json_encode($district_data),
            'accept_channel' => $accept_channel_data,
            'payment_channel' => $payment_channel_data
        ]);
    }

    private function switchState ($state, $type, $get=false) {
        $arr = [];
        
        if ($type == 'status') {
            $arr = [
                '1' => '正常',
                '2' => '禁用',
            ];
        }
        
        if ($type == 'btn_type') {
            $arr = [
                '1' => 'btn-success',
                '2' => 'btn-danger',                
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
            $item->delay_time_str = date('Y-m-d', $item->delay_time);

            if(strpos(','.$item->districts_ids.',',',1,') !== false){
                $item->districts_ids_str = "ALL";
            }else{
                $item->districts_ids_str = "--";
            }
            
            $item->accept_channel_str = $item->accept_channels !== null ? : "ALL";
            $item->payment_channel_str = $item->payment_channels !== null ? : "ALL";          
            
            $item->btn = $this->switchState($item->status, 'btn_type');
            $item->btn_name = $this->switchState($item->status, 'status');
        });

        return $paginate;
    }
}
