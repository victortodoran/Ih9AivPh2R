<?php
declare(strict_types=1);


namespace App\Domain\Skill;

abstract class AbstractAttackSkill extends AbstractSkill
{
    public const SKILL_TYPE = 'attack';

    abstract public function applySkill(float $attackValue): float;
}