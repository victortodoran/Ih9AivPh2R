<?php
declare(strict_types=1);

namespace App\Domain\Skill;

/**
 * MagicShield skill causes only half of the damage when an enemy attacks
 */
class MagicShield extends AbstractDefenceSkill
{
    public const IDENTIFIER = 'magic_shield';

    public function applySkill(float $defenceValue, float $opponentAttackValue): float
    {
        return $defenceValue + $opponentAttackValue / 2;
    }
}