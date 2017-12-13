<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PaymentChannelCreateRequest;
use App\Http\Requests\PaymentChannelUpdateRequest;
use App\Repositories\PaymentChannelRepository;
use App\Repositories\AcceptChannelRepository;
use App\Validators\PaymentChannelValidator;


class PaymentChannelsController extends Controller
{

    /**
     * @var PaymentChannelRepository
     */
    protected $repository;

    /**
     * @var PaymentChannelValidator
     */
    protected $validator;

    public function __construct(PaymentChannelRepository $repository, PaymentChannelValidator $validator)
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
        
        $paymentChannels = $this->repository->paginate($request->limit);

        return $this->tansform($paymentChannels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PaymentChannelCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentChannelCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $paymentChannel = $this->repository->create($request->all());

            $paymentChannel->accept_channels()->attach($request->accept_id);

            $response = [
                'message' => 'PaymentChannel created.',
                'data'    => $paymentChannel->toArray(),
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
        $paymentChannel = $this->repository->with("accept_channels")->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $paymentChannel,
            ]);
        }

        return view('paymentChannels.show', compact('paymentChannel'));
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

        $paymentChannel = $this->repository->find($id);

        return view('paymentChannels.edit', compact('paymentChannel'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PaymentChannelUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PaymentChannelUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $paymentChannel = $this->repository->update($request->all(), $id);

            $paymentChannel->accept_channels()->sync($request->accept_id);

            $response = [
                'message' => 'PaymentChannel updated.',
                'data'    => $paymentChannel->toArray(),
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

        //delete relations
        $data->accept_channels()->detach();

        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'PaymentChannel deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PaymentChannel deleted.');
    }

    /*******************************************other resource******************************************************/

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

    protected function tansform($paginate)
    {
        $paginate->getCollection()->each(function($item, $key){
            $item->pay_sign_str = $this->switchState($item->pay_sign, 'pay_sign');
            $item->pay_type_str = $this->switchState($item->pay_type, 'pay_type');
        });

        return $paginate;
    }

    public function view(AcceptChannelRepository $AcceptChannelRepository)
    {
        $accept_channels = $AcceptChannelRepository->all();

        $data['accept_list'] = $accept_channels;
        
        $data['pay_sign_list'] = $this->switchState(null, 'pay_sign', true);
        $data['pay_type_list'] = $this->switchState(null, 'pay_type', true);

        return view('system.payment_channel', $data);
    }
}
