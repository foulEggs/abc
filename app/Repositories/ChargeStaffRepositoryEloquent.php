<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ChargeStaffRepository;
use App\ChargeStaff;
use App\Validators\ChargeStaffValidator;

/**
 * Class ChargeStaffRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ChargeStaffRepositoryEloquent extends BaseRepository implements ChargeStaffRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ChargeStaff::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ChargeStaffValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
