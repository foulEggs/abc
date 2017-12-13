<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AcceptChannelRepository;
use App\AcceptChannel;
use App\Presenters\AcceptChannelPresenter;
use App\Validators\AcceptChannelValidator;

/**
 * Class AcceptChannelRepositoryEloquent
 * @package namespace App\Repositories;
 */
class AcceptChannelRepositoryEloquent extends BaseRepository implements AcceptChannelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AcceptChannel::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return AcceptChannelValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
