<?php

namespace App;

class Utilities
{
    public static function getMoney(int|float $money): string {
        if (!isset(static::$moneyFormatter)) {
            static::$moneyFormatter = new \NumberFormatter('lt-LT', \NumberFormatter::CURRENCY);
        }

        return static::$moneyFormatter->format($money / 100);
    }
    private static \NumberFormatter $moneyFormatter;
}
