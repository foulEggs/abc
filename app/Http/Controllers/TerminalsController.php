<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TerminalCreateRequest;
use App\Http\Requests\TerminalUpdateRequest;
use App\Repositories\TerminalRepository;
use App\Validators\TerminalValidator;


class TerminalsController extends Controller
{

    /**
     * @var TerminalRepository
     */
    protected $repository;

    /**
     * @var TerminalValidator
     */
    protected $validator;

    public function __construct(TerminalRepository $repository, TerminalValidator $validator)
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

        $terminals = $this->repository->paginate($request->limit);

        return $this->transform($terminals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TerminalCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TerminalCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
             $data = $request->all();
            $data['delivery_time'] = strtotime($data['delivery_time']);
            $terminal = $this->repository->create($data);

            $response = [
                'message' => 'Terminal created.',
                'data'    => $terminal->toArray(),
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
        $terminal = $this->repository->find($id);
        $terminal->delivery_time = date('Y-m-d', $terminal->delivery_time);
        if (request()->wantsJson()) {

            return response()->json([
                'data' => $terminal,
            ]);
        }

        return view('terminals.show', compact('terminal'));
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

        $terminal = $this->repository->find($id);

        return view('terminals.edit', compact('terminal'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  TerminalUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(TerminalUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $data = $request->all();
            $data['delivery_time'] = strtotime($data['delivery_time']);
            $terminal = $this->repository->update($data, $id);

            $response = [
                'message' => 'Terminal updated.',
                'data'    => $terminal->toArray(),
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
                'message' => 'Terminal deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Terminal deleted.');
    }

    /***************************************other resource************************************************/

    private function switchState ($state, $type, $get=false) {
        $arr = [];
        
        if ($type == 'status') {
            $arr = [
                '1' => '正常',
                '2' => '维修',
                '3' => '遗失',
                '4' => '异常',
                '5' => '收回'
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
            $item->delivery_time_str = date('Y-m-d', $item->delivery_time);

            $item->status_str = $this->switchState($item->status, 'status');
        });

        return $paginate;
    }


    public function view()
    {
        $data['status_list'] = $this->switchState(null, 'status', true);

        return view('terminal.index', $data);
    }
}
