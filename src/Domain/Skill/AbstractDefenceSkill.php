<?php
declare(strict_types=1);


namespace App\Domain\Skill;

abstract class AbstractDefenceSkill extends AbstractSkill
{
    public const SKILL_TYPE = 'defence';

    abstract public function applySkill(float $defenceValue, float $opponentAttackValue): float;
}