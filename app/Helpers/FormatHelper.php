<?php

namespace App\Helpers;

class FormatHelper
{
    public static function number($value)
    {
        return number_format($value, 0, ',', '.');
    }
}
