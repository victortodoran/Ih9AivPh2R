<?php
declare(strict_types=1);


namespace App\Domain;


use App\Domain\Skill\AbstractSkill;

class Character
{
    private string $name;
    private int $health;
    private int $strength;
    private int $defence;
    private int $speed;
    private int $luck;
    private array $attackSkills = [];
    private array $defenseSkills = [];

    public function __construct(
        string $name,
        int $health,
        int $strength,
        int $defence,
        int $speed,
        int $luck
    ) {
        $this->name = $name;
        $this->health = $health;
        $this->strength = $strength;
        $this->defence = $defence;
        $this->speed = $speed;
        $this->luck = $luck;
    }

    public function addSkill(AbstractSkill $skill): self
    {
        if($skill->getType() === AbstractSkill::TYPE_ATTACK) {
            $this->attackSkills[] = $skill;
            return $this;
        }

        $this->defenseSkills[] = $skill;
        return $this;
    }
}