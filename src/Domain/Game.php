<?php
declare(strict_types=1);

namespace App\Domain;

use App\Api\ChanceCalculatorInterface;
use App\Domain\DTO\Round;
use App\Exception\GameOverException;
use App\Exception\InvalidRoundConstructorParamsException;
use SplQueue;
/**
 * Given two characters executes a game
 */
class Game
{
    /**
     * All the events that occurred in a game
     */
    private SplQueue $rounds;
    /**
     * Protagonists of the game
     */
    private SplQueue $characters;
    /**
     * Stats of characters at the beginning of the game
     */
    private array $characterInitialStats;
    private int $maxNumberOfRounds;
    private bool $isGameOver;
    private ?Character $winner;
    private ChanceCalculatorInterface $chanceCalculator;
    private int $roundNumber;

    public function __construct(
        ChanceCalculatorInterface $chanceCalculator,
        Character $characterOne,
        Character $characterTwo,
        int $maxNumberOfRounds
    ) {
        $this->winner = null;
        $this->characters = new SplQueue();
        $this->rounds = new SplQueue();
        $this->maxNumberOfRounds = $maxNumberOfRounds;
        $this->chanceCalculator = $chanceCalculator;
        $this->roundNumber = 1;
        $this->isGameOver = false;

        if($this->isCharacterOneFirst($characterOne, $characterTwo)) {
            $this->characters->enqueue($characterOne);
            $this->characters->enqueue($characterTwo);
            $this->recordInitialCharacterStats([$characterOne, $characterTwo]);
            return;
        }

        $this->characters->enqueue($characterTwo);
        $this->characters->enqueue($characterOne);
        $this->recordInitialCharacterStats([$characterTwo, $characterOne]);
    }

    /**
     * @throws GameOverException
     * @throws InvalidRoundConstructorParamsException
     */
    public function executeRound(): Round
    {
        if($this->roundNumber > $this->maxNumberOfRounds || $this->isGameOver) {
            throw new GameOverException("Game Over.");
        }

        $attacker = $this->characters->dequeue();
        $defender = $this->characters->dequeue();

        if($this->chanceCalculator->areOddsInFavour($defender->getLuck())) {
            $round = new Round(
                $this->roundNumber,
                $attacker->getName(),
                $defender->getName(),
                true,
                $defender->getHealth()
            );
            $this->rounds->enqueue($round);

            $this->characters->enqueue($defender);
            $this->characters->enqueue($attacker);
            $this->roundNumber++;
            return $round;
        }

        $attack = $attacker->computeAttack();
        $defense = $defender->takeDamage($attack->getValue());

        if($defender->isCharacterDefeated()) {
            $this->isGameOver = true;
            $this->winner = $attacker;
        }

        $round = new Round(
            $this->roundNumber,
            $attacker->getName(),
            $defender->getName(),
            false,
            $defender->getHealth(),
            $attack,
            $defense
        );
        $this->rounds->enqueue($round);
        $this->characters->enqueue($defender);
        $this->characters->enqueue($attacker);
        $this->roundNumber++;

        if($this->roundNumber === $this->maxNumberOfRounds) {
            $this->isGameOver = true;
        }

        return $round;
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    private function isCharacterOneFirst(Character $characterOne, Character $characterTwo): bool
    {
        /*
         * There is no differentiating criteria(given in the requirements) when deciding which character starts first
         * And both speed and luck are equal, we choose characterOne
         */
        if($characterOne->getSpeed() === $characterTwo->getSpeed()) {
            return $characterOne->getLuck() >= $characterTwo->getLuck();
        }

        return $characterOne->getSpeed() > $characterTwo->getSpeed();
    }

    public function getWinner(): ?Character
    {
        return $this->winner;
    }

    public function getInitialCharacterStats(): array
    {
        return $this->characterInitialStats;
    }

    /**
     * @param Character[] $characters
     */
    private function recordInitialCharacterStats(array $characters): void
    {
        foreach ($characters as $character) {
            $this->characterInitialStats[$character->getName()] = [
                'name' => $character->getName(),
                'health' => $character->getHealth(),
                'strength' => $character->getStrength(),
                'defence' => $character->getDefence(),
                'speed' => $character->getSpeed(),
                'luck' => $character->getLuck()
            ];
        }
    }
}