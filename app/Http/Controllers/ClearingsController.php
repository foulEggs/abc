<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\ClearingRepository;
use App\Repositories\DistrictRepository;
use App\Criteria\DateSectionCriteria;
use App\Repositories\ChargeStaffRepository;


class ClearingsController extends Controller
{

    /**
     * @var ClearingRepository
     */
    protected $repository;

    /**
     * @var ClearingValidator
     */
    protected $validator;

    public function __construct(ClearingRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detailIndex(Request $request)
    {
        $this->repository->pushCriteria(app(DateSectionCriteria::class));
        $clearings = $this->repository->paginate($request->limit);

        return $clearings;
    }

    public function allIndex(Request $request)
    {
        //$this->repository->
    }

    public function detailView()
    {
        return view('clearing.clearing_detail');
    }
}
