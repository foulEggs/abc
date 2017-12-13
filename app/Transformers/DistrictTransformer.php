<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\District;

/**
 * Class DistrictTransformer
 * @package namespace App\Transformers;
 */
class DistrictTransformer extends TransformerAbstract
{

    /**
     * Transform the District entity
     * @param App\Models\District $model
     *
     * @return array
     */
    public function transform(District $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
