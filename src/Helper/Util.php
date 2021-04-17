<?php
declare(strict_types=1);


namespace App\Helper;


use App\Exception\InvalidChanceValueException;

class Util
{
    /**
     * Given that chance with which an event occurs
     * decides whether the event must occur or not.
     */
    public static function areOddsInFavour(float $probability): bool
    {
        if($probability === 0.0) {
            return false;
        }
        return (float) rand(1,100) <= $probability;
    }
}