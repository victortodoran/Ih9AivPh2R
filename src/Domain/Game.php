<?php
declare(strict_types=1);

namespace App\Domain;

use App\Domain\DTO\Round;
use App\Helper\Util;
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
    private bool $isGameOver = false;
    private ?Character $winner;

    public function __construct(
        Character $characterOne,
        Character $characterTwo,
        int $maxNumberOfRounds
    ) {
        $this->winner = null;
        $this->characters = new SplQueue();
        $this->rounds = new SplQueue();
        $this->maxNumberOfRounds = $maxNumberOfRounds;

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

    public function execute(): void
    {
        if($this->isGameOver) {
            return;
        }

        $roundNumber = 1;
        /**
         * @var Character $attacker
         * @var Character $defender
         */
        while($roundNumber <= $this->maxNumberOfRounds && !$this->isGameOver) {
            $attacker = $this->characters->dequeue();
            $defender = $this->characters->dequeue();

            if(Util::areOddsInFavour($defender->getLuck())) {
                $this->rounds->enqueue(
                    new Round($roundNumber,$attacker->getName(),$defender->getName(), true, $defender->getHealth())
                );

                $this->characters->enqueue($defender);
                $this->characters->enqueue($attacker);
                $roundNumber++;
                continue;
            }

            $attack = $attacker->computeAttack();
            $defense = $defender->takeDamage($attack->getValue());

            if($defender->isCharacterDefeated()) {
                $this->isGameOver = true;
                $this->winner = $attacker;
            }

            $this->rounds->enqueue(
                new Round(
                    $roundNumber,
                    $attacker->getName(),
                    $defender->getName(),
                    false,
                    $defender->getHealth(),
                    $attack,
                    $defense
                )
            );
            
            $this->characters->enqueue($defender);
            $this->characters->enqueue($attacker);
            $roundNumber++;
        }

        $this->isGameOver = true;
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

    public function getRounds(): SplQueue
    {
        return $this->rounds;
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
     *
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