<?php

namespace App\Services;

class BaseService
{
    public function second2time($second)
    {
        $second = intval($second);
        if (!$second) return '';

        $hours = floor($second / 3600);
        $hours = $hours ? str_pad($hours, 2, '0', STR_PAD_LEFT) : 0;
        $second = $second % 3600;
        $minutes = floor($second / 60);
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $seconds = $second % 60;
        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

        return implode(':', $hours ? compact('hours', 'minutes', 'seconds') : compact('minutes', 'seconds'));
    }
}