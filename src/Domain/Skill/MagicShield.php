<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class MagicShield extends AbstractSkill
{
    public const IDENTIFIER = 'magic_shield';

    public function getType(): string
    {
        return self::TYPE_DEFENSE;
    }

    function addSkillValue($value): int
    {
        // TODO: Implement computeSkillValue() method.
        return $value;
    }
}