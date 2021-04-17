<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class MagicShield extends AbstractSkill
{
    public const IDENTIFIER = 'magic_shield';

    function addSkillValue(float $value): float
    {
        // TODO: Implement computeSkillValue() method.
        return $value;
    }
}