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
    public static function areOddsInFavour(int $chance): bool
    {
        if($chance === 0) {
            return false;
        }
        return $chance <= rand(1,100);
    }
}