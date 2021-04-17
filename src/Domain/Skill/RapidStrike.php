<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class RapidStrike extends AbstractSkill
{
    public const IDENTIFIER = 'rapid_strike';

    /*
     * The introduction of new skills might ask for a change in the way skills are applied.
     * Any other skill that increases attack value must be applied before the rapid strike skill.
     */
    function addAttackValue(float $attackValue): float
    {
        return $attackValue * 2;
    }

    /**
     *
     */
    function addDefenceValue(float $defenceValue, float $opponentAttackValue): float
    {
        return $defenceValue;
    }
}