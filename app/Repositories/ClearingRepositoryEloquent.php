<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ClearingRepository;
use App\Clearing;

/**
 * Class ClearingRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ClearingRepositoryEloquent extends BaseRepository implements ClearingRepository
{
    protected $fieldsSearchable = ['clear_type','to_order_id','charge_time'];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Clearing::class;
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
