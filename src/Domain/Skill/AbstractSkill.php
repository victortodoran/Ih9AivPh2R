<?php
declare(strict_types=1);

namespace App\Domain\Skill;

use App\Exception\InvalidSkillValuesException;
use App\Helper\Util;

abstract class AbstractSkill
{
    public const TYPE_DEFENSE = 'defense';
    public const TYPE_ATTACK = 'attack';

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
     * defense|attack
     */
    private string $type;

    /**
     * Skill constructor.
     * @throws InvalidSkillValuesException
     */
    public function __construct(string $type, float $chance)
    {
        $this->validateConstructorData($type, $chance);

        $this->type = $type;
        $this->chance = $chance;
        $this->skillLabel = str_replace('_', ' ', static::IDENTIFIER);
    }

    abstract function addSkillValue(float $value): float;

    public function getType(): string
    {
        return $this->type;
    }

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
     * @param string $type
     * @param float $chance
     * @throws InvalidSkillValuesException
     */
    private function validateConstructorData(string $type, float $chance): void
    {
        if($type !== self::TYPE_DEFENSE || $type !== self::TYPE_ATTACK) {
            throw new InvalidSkillValuesException("Invalid skill type '{$type}' provided");
        }

        if($chance < 0 || $chance > 100) {
            throw new InvalidSkillValuesException("Chance can not be smaller than 0 or greater than 100");
        }
    }
}