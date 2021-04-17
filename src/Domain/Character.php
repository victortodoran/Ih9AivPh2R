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
    private array $attackSkills = [];
    private array $defenseSkills = [];

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
        return $this->getHealth();
    }

    public function computeAttack(): Action
    {
        // TODO IMPLEMENT
        return new Action(0, []);
    }

    public function computeDefense(): Action
    {
        // TODO IMPLEMENT
        return new Action(0, []);
    }

    /**
     * @throws CharacterIsDeadException
     */
    public function takeDamage(float $damage): float
    {
        // TODO IMPLEMENT
        return $this->health;
    }
}