<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ClearRelateRepository;
use App\ClearRelate;
use App\Validators\ClearRelateValidator;

/**
 * Class ClearRelateRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ClearRelateRepositoryEloquent extends BaseRepository implements ClearRelateRepository
{
    protected $fieldsSearchable = ['to_clearing_id','clear_type','charge_time','clear_relate_id'];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ClearRelate::class;
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
