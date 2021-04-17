<?php
declare(strict_types=1);

namespace App\Domain;

use App\Domain\DTO\Action;
use App\Domain\Skill\AbstractSkill;
use App\Exception\CharacterIsDeadException;

/**
 * A character is one of two possible protagonists of the game
 * It can be either Orderus or a Beast
 */
class Character
{
    private string $name;
    private float $health;
    private float $strength;
    private float $defence;
    private float $speed;
    private float $luck;
    /**
     * @var AbstractSkill[]
     */
    private array $skills;

    public function __construct(
        string $name,
        float $health,
        float $strength,
        float $defence,
        float $speed,
        float $luck
    ) {
        $this->name = $name;
        $this->health = $health;
        $this->strength = $strength;
        $this->defence = $defence;
        $this->speed = $speed;
        $this->luck = $luck;
        $this->skills = [];
    }

    public function addSkill(AbstractSkill $skill): self
    {
        $this->skills[] = $skill;
        return $this;
    }

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function getLuck(): float
    {
        return $this->luck;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHealth(): float
    {
        return $this->health;
    }

    public function computeAttack(): Action
    {
        $attackValue = $this->strength;
        $appliedSkills = [];
        foreach ($this->skills as $skill) {
            if($skill->doesSkillApply()) {
                $attackValue = $skill->addAttackValue($attackValue);
                $appliedSkills[] = $skill->getSkillLabel();
            }
        }

        return new Action($attackValue, $appliedSkills);
    }

    /**
     * @throws CharacterIsDeadException
     */
    public function takeDamage(float $damage): Action
    {
        $defenceValue = $this->defence;
        $appliedSkills = [];
        foreach($this->skills as $skill) {
            if($skill->doesSkillApply()) {
                $defenceValue = $skill->addDefenceValue($defenceValue, $damage);
                $appliedSkills[] = $skill->getSkillLabel();
            }
        }
        return new Action($defenceValue, $appliedSkills);
    }
}