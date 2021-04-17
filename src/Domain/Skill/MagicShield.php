<?php
declare(strict_types=1);

namespace App\Domain\Skill;

class MagicShield extends AbstractSkill
{
    public const IDENTIFIER = 'magic_shield';

    public function addAttackValue(float $attackValue): float
    {
        return $attackValue;
    }

    public function addDefenseValue(float $defenceValue, float $opponentAttackValue): float
    {
        return $defenceValue + $opponentAttackValue / 2;
    }
}