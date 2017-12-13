<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\RepositoryCreateRequest;
use App\Http\Requests\RepositoryUpdateRequest;
use App\Repositories\RepositoryRepository;
use App\Validators\RepositoryValidator;


class RepositoriesController extends Controller
{

    /**
     * @var RepositoryRepository
     */
    protected $repository;

    /**
     * @var RepositoryValidator
     */
    protected $validator;

    public function __construct(RepositoryRepository $repository, RepositoryValidator $validator)
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
        $repositories = $this->repository->paginate($request->limit);

        return $repositories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RepositoryCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RepositoryCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $repository = $this->repository->create($request->all());

            $response = [
                'message' => 'Repository created.',
                'data'    => $repository->toArray(),
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
        $repository = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $repository,
            ]);
        }

        return view('repositories.show', compact('repository'));
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

        $repository = $this->repository->find($id);

        return view('repositories.edit', compact('repository'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  RepositoryUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(RepositoryUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $repository = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Repository updated.',
                'data'    => $repository->toArray(),
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
                'message' => 'Repository deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Repository deleted.');
    }

    public function view()
    {
        return view('system.repository');
    }
}
