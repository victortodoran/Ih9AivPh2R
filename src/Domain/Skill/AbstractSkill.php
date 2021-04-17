<?php
declare(strict_types=1);

namespace App\Domain\Skill;

use App\Exception\InvalidSkillValuesException;

abstract class AbstractSkill
{
    public const TYPE_DEFENSE = 'defense';
    public const TYPE_ATTACK = 'attack';

    public const IDENTIFIER = 'abstract_skill';

    /**
     * Chance that the skill applies at each event.
     */
    private int $chance;
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
    public function __construct(string $type, int $chance)
    {
        $this->validateConstructorData($type, $chance);

        $this->type = $type;
        $this->chance = $chance;
        $this->skillLabel = str_replace('_', ' ', static::IDENTIFIER);
    }

    abstract public function getType(): string;
    abstract function addSkillValue(int $value): int;

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
        return false;
    }

    /**
     * @param string $type
     * @param int $chance
     * @throws InvalidSkillValuesException
     */
    private function validateConstructorData(string $type, int $chance): void
    {
        if($type !== self::TYPE_DEFENSE || $type !== self::TYPE_ATTACK) {
            throw new InvalidSkillValuesException("Invalid skill type '{$type}' provided");
        }

        if($chance < 0 || $chance > 100) {
            throw new InvalidSkillValuesException("Chance can not be smaller than 0 or greater than 100");
        }
    }
}