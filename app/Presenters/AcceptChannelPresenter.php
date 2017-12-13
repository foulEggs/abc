<?php

namespace App\Presenters;

use App\Transformers\AcceptChannelTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AcceptChannelPresenter
 *
 * @package namespace App\Presenters;
 */
class AcceptChannelPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AcceptChannelTransformer();
    }
}
