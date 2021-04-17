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

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getLuck(): int
    {
        return $this->luck;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHealth(): int
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
    public function takeDamage(int $damage): int
    {
        // TODO IMPLEMENT
        return $this->health;
    }
}