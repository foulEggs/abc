<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\OrderRepository;
use App\Validators\OrderValidator;
use App\Order;

/**
 * Class OrderSerialRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    protected $fieldsSearchable= ['sys_order_num','trade_status','user_no','accept_channel','orders_serial.payment_channel'];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
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

    public function leftJoin($table, $relation_column)
    {
        $this->model = $this->model->leftJoin($table, $relation_column[0], "=", $relation_column[1]);

        return $this;
    }

    public function count($columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();
        
        $results = $this->model->count($columns);
        
        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }

    public function sum($columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();
        
        $results = $this->model->sum($columns);
        
        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
