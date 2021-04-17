<?php
declare(strict_types=1);

namespace App\Domain\Skill;

use App\Api\ChanceCalculatorInterface;
use App\Exception\InvalidSkillValuesException;

abstract class AbstractSkill
{
    public const IDENTIFIER = 'abstract_skill';
    public const SKILL_TYPE = 'abstract';

    /**
     * Chance that the skill applies at each event.
     */
    private float $chance;
    /**
     * The label will be used to describe what happened in each round.
     */
    private string $skillLabel;
    /**
     * The priority is used to determine which is skill(from a number of skills) is applied first.
     * Higher is better.
     */
    private int $priority;
    private ChanceCalculatorInterface $chanceCalculator;

    /**
     * Skill constructor.
     * @throws InvalidSkillValuesException
     */
    public function __construct(ChanceCalculatorInterface $chanceCalculator, float $chance, int $priority)
    {
        $this->validateConstructorData($chance);

        $this->chance = $chance;
        $this->priority = $priority;
        $this->skillLabel = strtoupper(static::IDENTIFIER);
        $this->chanceCalculator = $chanceCalculator;
    }

    public function getSkillLabel(): string
    {
        return $this->skillLabel;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getType(): string
    {
        return static::SKILL_TYPE;
    }

    /**
     * At each event there is $this->chance that the skill applies.
     * The method determines whether a skill applies at a given event
     */
    public function doesSkillApply(): bool
    {
        return $this->chanceCalculator->areOddsInFavour($this->chance);
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