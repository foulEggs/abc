<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PaymentChannelRepository;
use App\PaymentChannel;
use App\Validators\PaymentChannelValidator;

/**
 * Class PaymentChannelRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PaymentChannelRepositoryEloquent extends BaseRepository implements PaymentChannelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PaymentChannel::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PaymentChannelValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
