<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\modelRepository;
use App\\Model;
use App\Validators\ModelValidator;

/**
 * Class ModelRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ModelRepositoryEloquent extends BaseRepository implements ModelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Model::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
