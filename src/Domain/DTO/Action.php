<?php
declare(strict_types=1);


namespace App\Domain\DTO;

/**
 * Used to transport the value of an attack|defense and the skills involved to determine that value.
 * Actions are part of Rounds which serve as historical data of Game's execution.
 * Actions must be immutable to ensure the historical accuracy of a Game's execution.
 */
class Action
{
    private float $value;
    private array $skills;

    public function __construct(
        float $value,
        array $skills
    ) {
        $this->value = $value;
        $this->skills = $skills;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getSkills(): array
    {
        return $this->skills;
    }

    public function wereSkillsUsed(): bool
    {
        return count($this->skills) !== 0;
    }
}