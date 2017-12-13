<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\uriRepository;
use App\Uri;
use App\Validators\UriValidator;

/**
 * Class UriRepositoryEloquent
 * @package namespace App\Repositories;
 */
class UriRepositoryEloquent extends BaseRepository implements UriRepository
{
    protected $fieldsSearchable = ['repository_id'];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Uri::class;
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
}
