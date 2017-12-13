<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
/**
 * Class OrderCriteria
 * @package namespace App\Criteria;
 */
class DateSectionCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, RepositoryInterface $repository, $where = [])
    {
        $date_search = explode(':', $this->request->get('date_search') ? : ':,');

        $date_section = explode(',', $date_search[1] ? : ",");

        if ( $date_section[0] && $date_section[1] ) {
            $model->whereBetween($date_search[0], array_map(function($v){
                if($v) return strtotime($v);
            },$date_section));
        } elseif ( $date_section[0] && !$date_section[1] ) {
            $model->where($date_search[0], ">", strtotime($date_section[0]));
        } elseif ( !$date_section[0] && $date_section[1] ) {
            $model->where($date_search[0], "<", strtotime($date_section[1]));
        }

        return $model;
    }
}
