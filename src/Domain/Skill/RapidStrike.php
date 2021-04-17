<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class RapidStrike extends AbstractSkill
{
    public const IDENTIFIER = 'rapid_strike';

    function addAttackValue(float $attackValue): float
    {
        return $attackValue * 2;
    }

    function addDefenceValue(float $defenceValue, float $opponentAttackValue): float
    {
        return $defenceValue;
    }
}