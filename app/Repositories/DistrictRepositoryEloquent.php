<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DistrictRepository;
use App\District;
use App\Validators\DistrictValidator;

/**
 * Class DistrictRepositoryEloquent
 * @package namespace App\Repositories;
 */
class DistrictRepositoryEloquent extends BaseRepository implements DistrictRepository
{

    protected $fieldsSearchable = [
        'name',
        'level'=>'>',
        'level'
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return District::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DistrictValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
