<?php
declare(strict_types=1);

namespace App\Api;

interface ChanceCalculatorInterface
{
    public function areOddsInFavour(float $probability): bool;
}