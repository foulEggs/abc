<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\repositoryRepository;
use App\Repository;
use App\Validators\RepositoryValidator;

/**
 * Class RepositoryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class RepositoryRepositoryEloquent extends BaseRepository implements RepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Repository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
