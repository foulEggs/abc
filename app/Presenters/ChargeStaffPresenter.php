<?php

namespace App\Presenters;

use App\Transformers\ChargeStaffTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ChargeStaffPresenter
 *
 * @package namespace App\Presenters;
 */
class ChargeStaffPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ChargeStaffTransformer();
    }
}
