<?php
declare(strict_types=1);

namespace App\Domain;

class Skill
{
    public const TYPE_DEFENSE = 'defense';
    public const TYPE_ATTACK = 'attack';

    private string $type;
    private int $chance;
    private int $value;

    /**
     * Skill constructor.
     */
    public function __construct(string $type, int $chance, int $value)
    {
        $this->type = $type;
        $this->chance = $chance;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->getType();
    }

    /**
     *
     * @return int
     */
    public function computeSkillValue(): int
    {
        return $this->doesSkillApply() ? $this->value : 0;
    }

    /**
     * At each event there is $this->chance that the skill applies.
     * The method determines whether a skill applies at a given event
     */
    private function doesSkillApply(): bool
    {
        return false;
    }
}