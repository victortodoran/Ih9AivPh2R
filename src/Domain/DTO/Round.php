<?php
declare(strict_types=1);


namespace App\Domain\DTO;

/**
 * Used to keep track of events during a game's execution.
 * Rounds can later be used to display a game's execution in whatever format needed.
 * As Rounds outlive a game and can be accessed(for output purposes) outside a game
 * we must ensure that the round is immutable, thus the lack of setters.
 */
class Round
{
    private int $roundNumber;
    private string $attackerName;
    private string $defenderName;
    private bool $defenderGotLucky;
    private float $defenderRemainingHealth;
    private ?Action $attack;
    private ?Action $defense;

    public function __construct(
        int $roundNumber,
        string $attackerName,
        string $defenderName,
        bool $defenderGotLucky,
        float $defenderRemainingHealth,
        ?Action $attack = null,
        ?Action $defense = null
    ) {
        $this->roundNumber = $roundNumber;
        $this->attackerName = $attackerName;
        $this->defenderName = $defenderName;
        $this->defenderGotLucky = $defenderGotLucky;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->defenderRemainingHealth = $defenderRemainingHealth;
    }

    /**
     * @return int
     */
    public function getRoundNumber(): int
    {
        return $this->roundNumber;
    }

    /**
     * @return string
     */
    public function getAttackerName(): string
    {
        return $this->attackerName;
    }

    /**
     * @return string
     */
    public function getDefenderName(): string
    {
        return $this->defenderName;
    }

    /**
     * @return bool
     */
    public function isDefenderGotLucky(): bool
    {
        return $this->defenderGotLucky;
    }

    /**
     * @return float
     */
    public function getDefenderRemainingHealth(): float
    {
        return $this->defenderRemainingHealth;
    }

    /**
     * @return Action|null
     */
    public function getAttack(): ?Action
    {
        return $this->attack;
    }

    /**
     * @return Action|null
     */
    public function getDefense(): ?Action
    {
        return $this->defense;
    }
}