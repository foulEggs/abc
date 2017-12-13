<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\UriCreateRequest;
use App\Http\Requests\UriUpdateRequest;
use App\Repositories\UriRepository;
use App\Repositories\RepositoryRepository;
use App\Validators\UriValidator;


class UrisController extends Controller
{

    /**
     * @var UriRepository
     */
    protected $repository;

    /**
     * @var UriValidator
     */
    protected $validator;

    public function __construct(UriRepository $repository, UriValidator $validator)
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
        $uris = $this->repository->with('repository')->paginate($request->limit);
        
        return $this->transform($uris);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UriCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UriCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $uri = $this->repository->create($request->all());

            $response = [
                'message' => 'Uri created.',
                'data'    => $uri->toArray(),
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
        $uri = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $uri,
            ]);
        }

        return view('uris.show', compact('uri'));
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

        $uri = $this->repository->find($id);

        return view('uris.edit', compact('uri'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UriUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(UriUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $uri = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Uri updated.',
                'data'    => $uri->toArray(),
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
                'message' => 'Uri deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Uri deleted.');
    }

    /*******************************************other resource******************************************************/

    private function switchState ($state, $type, $get=false) {
        $arr = [];
        
        if ($type == 'status') {
            $arr = [
                '1' => '正常',
                '2' => '禁用'
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
            $item->status_str = $this->switchState($item->status, 'status');
            $item->repository_name = $item->repository->name;
        });

        return $paginate;
    }

    public function view(RepositoryRepository $Repository)
    {
        $repository_list = $Repository->all();
        return view('system.uri', ['repository_list' => $repository_list]);
    }
}
