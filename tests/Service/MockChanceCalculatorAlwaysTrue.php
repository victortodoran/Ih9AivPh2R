<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Api\ChanceCalculatorInterface;

class MockChanceCalculatorAlwaysTrue implements ChanceCalculatorInterface
{
    public function areOddsInFavour(float $probability): bool
    {
        return true;
    }
}