<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class RapidStrike extends AbstractSkill
{
    public const IDENTIFIER = 'rapid_strike';

    public function getType(): string
    {
        return self::TYPE_ATTACK;
    }

    function addSkillValue(int $value): int
    {
        // TODO: Implement addSkillValue() method.
        return $value;
    }
}