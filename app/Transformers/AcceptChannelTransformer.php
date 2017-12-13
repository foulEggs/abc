<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\AcceptChannel;

/**
 * Class AcceptChannelTransformer
 * @package namespace App\Transformers;
 */
class AcceptChannelTransformer extends TransformerAbstract
{

    /**
     * Transform the AcceptChannel entity
     * @param App\\AcceptChannel $model
     *
     * @return array
     */
    public function transform(AcceptChannel $model)
    {
        return [
            'id'         => $model->id,
            /* place your other model properties here */

            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
