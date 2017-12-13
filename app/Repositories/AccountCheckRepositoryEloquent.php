<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AccountCheckRepository;
use App\AccountCheck;
use App\Validators\AccountCheckValidator;

/**
 * Class AccountCheckRepositoryEloquent
 * @package namespace App\Repositories;
 */
class AccountCheckRepositoryEloquent extends BaseRepository implements AccountCheckRepository
{
    protected $fieldsSearchable = ['create_time'];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AccountCheck::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function groupBy($column)
    {
        $this->model = $this->model->groupBy($column);

        return $this;
    }


    public function select($columns)
    {
        $this->model = $this->model->select(\DB::raw($columns));

        return $this;
    }
}
