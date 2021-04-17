<?php
declare(strict_types=1);

namespace App\Domain\Skill;

use App\Exception\InvalidSkillValuesException;
use App\Helper\Util;

abstract class AbstractSkill
{
    public const IDENTIFIER = 'abstract_skill';

    /**
     * Chance that the skill applies at each event.
     */
    private float $chance;
    /**
     * The label will be used to describe what happened in each round.
     */
    private string $skillLabel;

    /**
     * Skill constructor.
     * @throws InvalidSkillValuesException
     */
    public function __construct(float $chance)
    {
        $this->validateConstructorData($chance);

        $this->chance = $chance;
        $this->skillLabel = strtoupper(static::IDENTIFIER);
    }

    abstract public function addAttackValue(float $attackValue): float;
    abstract public function addDefenceValue(float $defenceValue, float $opponentAttackValue): float;

    public function getSkillLabel(): string
    {
        return $this->skillLabel;
    }

    /**
     * At each event there is $this->chance that the skill applies.
     * The method determines whether a skill applies at a given event
     */
    public function doesSkillApply(): bool
    {
        return Util::areOddsInFavour($this->chance);
    }

    /**
     * @param float $chance
     * @throws InvalidSkillValuesException
     */
    private function validateConstructorData(float $chance): void
    {
        if($chance < 0 || $chance > 100) {
            throw new InvalidSkillValuesException("Chance can not be smaller than 0 or greater than 100");
        }
    }
}