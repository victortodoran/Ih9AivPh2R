<?php
declare(strict_types=1);

namespace App\Domain;

use App\Domain\DTO\Action;
use App\Domain\Skill\AbstractAttackSkill;
use App\Domain\Skill\AbstractDefenceSkill;
use SplPriorityQueue;

/**
 * A character is one of two possible protagonists of the game. It can be either Orderus or a Beast.
 * There are not enough differences between a Champion(Orderus) and a Beast to justify separate entities for it.
 * There is a high probability that business requirements will introduce skills for Beasts in the future.
 */
class Character
{
    private string $name;
    private float $health;
    private float $strength;
    private float $defence;
    private float $speed;
    private float $luck;

    /*
     * The priority in which skills are applied counts.
     * see documentation.txt for more details.
     */
    private SplPriorityQueue $attackSkills;
    private SplPriorityQueue $defenceSkills;

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

        $this->attackSkills = new SplPriorityQueue();
        $this->defenceSkills = new SplPriorityQueue();
    }

    public function addAttackSkill(AbstractAttackSkill $skill): self
    {
        $this->attackSkills->insert($skill, $skill->getPriority());
        return $this;
    }

    public function addDefenceSkill(AbstractDefenceSkill $skill): self
    {
        $this->defenceSkills->insert($skill, $skill->getPriority());
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

    public function getStrength(): float
    {
        return $this->strength;
    }

    public function getDefence(): float
    {
        return $this->defence;
    }

    public function computeAttack(): Action
    {
        $attackValue = $this->strength;
        $appliedSkills = [];
        /** @var AbstractAttackSkill $skill */
        foreach (clone $this->attackSkills as $skill) {
            if($skill->doesSkillApply()) {
                $attackValue = $skill->applySkill($attackValue);
                $appliedSkills[] = $skill->getSkillLabel();
            }
        }

        return new Action($attackValue, $appliedSkills);
    }

    public function takeDamage(float $attackValue): Action
    {
        if($this->isCharacterDefeated()) {
            return new Action(0, []);
        }

        $defenceValue = $this->defence;
        $appliedSkills = [];
        /** @var AbstractDefenceSkill $skill */
        foreach(clone $this->defenceSkills as $skill) {
            if($skill->doesSkillApply()) {
                $defenceValue = $skill->applySkill($defenceValue, $attackValue);
                $appliedSkills[] = $skill->getSkillLabel();
            }
        }

        $damage = $attackValue - $defenceValue > 0 ? $attackValue - $defenceValue : 0.0;
        $this->health = $this->health - $damage > 0 ? $this->health - $damage : 0.0;

        return new Action($defenceValue, $appliedSkills);
    }

    public function isCharacterDefeated(): bool
    {
        return $this->health === 0.0;
    }
}