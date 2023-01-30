<?php

namespace App\Traits;

trait ResponseTrait
{
    public function formatReturn(array $params)
    {
        if (!isset($params['code'])) {
            $params['code'] = 0;
        }

        if (isset($params['state']) && $params['state'] == true && !isset($params['data'])) {
            $params['data'] = [];
        }

        return response()->json($params);
    }
}
