<?php
declare(strict_types=1);


namespace App\Domain;


class Character
{
    private int $health;
    private int $strength;
    private int $defence;
    private int $speed;
    private int $luck;
    private array $attackSkills = [];
    private array $defenseSkills = [];

    /**
     * Character constructor.
     * @param int $health
     * @param int $strength
     * @param int $defence
     * @param int $speed
     * @param int $luck
     */
    public function __construct(int $health, int $strength, int $defence, int $speed, int $luck)
    {
        $this->health = $health;
        $this->strength = $strength;
        $this->defence = $defence;
        $this->speed = $speed;
        $this->luck = $luck;
    }

    public function addSkill(Skill $skill): self
    {
        if($skill->getType() === Skill::TYPE_ATTACK) {
            $this->attackSkills[] = $skill;
            return $this;
        }

        $this->defenseSkills[] = $skill;
        return $this;
    }
}