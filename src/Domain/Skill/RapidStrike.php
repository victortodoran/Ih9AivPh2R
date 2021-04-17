<?php
declare(strict_types=1);

namespace App\Domain\Skill;

/**
 * RapidStrike: Strike twice while it’s his turn to attack;
 */
class RapidStrike extends AbstractAttackSkill
{
    public const IDENTIFIER = 'rapid_strike';

    public function applySkill(float $attackValue): float
    {
        return $attackValue * 2;
    }
}