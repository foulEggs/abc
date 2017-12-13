<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\AcceptChannelCreateRequest;
use App\Http\Requests\AcceptChannelUpdateRequest;
use App\Repositories\AcceptChannelRepository;
use App\Repositories\DistrictRepository;
use App\Validators\AcceptChannelValidator;


class AcceptChannelsController extends Controller
{

    /**
     * @var AcceptChannelRepository
     */
    protected $repository;

    /**
     * @var AcceptChannelValidator
     */
    protected $validator;

    public function __construct(AcceptChannelRepository $repository, AcceptChannelValidator $validator)
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
        //$this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $acceptChannels = $this->repository->paginate($request->limit);

        return $this->transform($acceptChannels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcceptChannelCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AcceptChannelCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $acceptChannel = $this->repository->create($request->all());

            //districts relations
            $acceptChannel->districts()->attach(explode(',', $request->districts_ids));

            $response = [
                'message' => 'AcceptChannel created.',
                'data'    => $acceptChannel->toArray(),
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
        $acceptChannel = $this->repository->with('districts')->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $acceptChannel,
            ]);
        }

        return view('acceptChannels.show', compact('acceptChannel'));
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

        $acceptChannel = $this->repository->find($id);

        return view('acceptChannels.edit', compact('acceptChannel'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  AcceptChannelUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(AcceptChannelUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $acceptChannel = $this->repository->update($request->all(), $id);

            $acceptChannel->districts()->sync(explode(',', $request->districts_ids));

            $response = [
                'message' => 'AcceptChannel updated.',
                'data'    => $acceptChannel->toArray(),
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
        $data->districts()->detach();

        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'AcceptChannel deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'AcceptChannel deleted.');
    }

    /*******************************************other resource******************************************************/

    public function view(DistrictRepository $DistrictRepository)
    {
        $districts = $DistrictRepository->all();

        return view('system.accept_channel', ['districts'=>json_encode($districts)]);
    }

    protected function switchState ($state, $type, $get=false) {
        $arr = [];
        
        if ($type == 'act_type') {
            $arr = [
                '1' => '满减',
                '2' => '满赠',
                '9' => '无活动',
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

            if($item->act_type == 1){
                $item->act_type_str = "-".$item->money;
            }else if($item->act_type == 2){
                $item->act_type_str = "+".$item->money;
            }else{
                $item->act_type_str = "无";
            }
            
        });

        return $paginate;
    }
}
