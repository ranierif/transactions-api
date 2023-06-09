<?php

namespace App\Traits;

trait FormatMoney
{
    /**
     * @param  int  $cents
     * @return string
     */
    public static function convertCentsToReal(int $cents): string
    {
        return number_format(($cents / 100), 2, ',', '.');
    }

    /**
     * @param  float  $real
     * @return int
     */
    public static function convertRealToCents(float $real): int
    {
        return $real * 100;
    }
}
