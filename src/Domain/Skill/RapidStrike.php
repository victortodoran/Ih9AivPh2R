<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class RapidStrike extends AbstractSkill
{
    public const IDENTIFIER = 'rapid_strike';

    function addSkillValue(float $value): float
    {
        return $value;
    }
}