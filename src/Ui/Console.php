<?php
declare(strict_types=1);


namespace App\Ui;

use App\Domain\DTO\Round;
use App\Domain\Game;
use App\Exception\GameOverException;
use App\Exception\InvalidRoundConstructorParamsException;

/**
 * Class Console used to output everything that happened during a finished game.
 */
class Console
{
    private const ROW_SEPARATOR = "##############################################################################" . PHP_EOL;

    /**
     * @throws GameOverException
     * @throws InvalidRoundConstructorParamsException
     */
    public function execute(Game $game)
    {
        $this->displayInitialStats($game->getInitialCharacterStats());

        while($game->isGameOver() === false)
        {
            $round = $game->executeRound();
            echo $this->getRoundHeader($round);
            if($round->wasDefenderLucky()) {
                echo $this->getDefenderWasLuckyMessage($round);
                continue;
            }
            echo $this->getFightRoundMessage($round);
        }

        if($game->getWinner()) {
            echo "The winner of the battle is {$game->getWinner()->getName()}" . PHP_EOL;
        } else {
            echo "The battle ended a draw" . PHP_EOL;
        }
    }

    private function getRoundHeader($round): string
    {
        $roundHeader  = self::ROW_SEPARATOR;
        $roundHeader .= "Round #{$round->getRoundNumber()}. {$round->getAttackerName()}(Attacker) vs {$round->getDefenderName()}(Defender)";
        $roundHeader .= PHP_EOL;
        return $roundHeader;
    }

    private function getDefenderWasLuckyMessage($round): string
    {
        $result =  "Defender {$round->getDefenderName()} used all his luck, evaded {$round->getAttackerName()}'s attack and took no damage" . PHP_EOL;
        $result .= self::ROW_SEPARATOR . PHP_EOL;
        return $result;
    }

    private function getFightRoundMessage($round): string
    {
        $result = "{$round->getAttackerName()} had an attack of {$round->getAttack()->getValue()}" . PHP_EOL;
        if($round->getAttack()->wereSkillsUsed()) {
            $result .= "Attacker used his core strength and the following skills " . implode(',', $round->getAttack()->getSkills()) . PHP_EOL;
        }

        $result .= "{$round->getDefenderName()} had a defence of {$round->getDefence()->getValue()}" . PHP_EOL;
        if($round->getDefence()->wereSkillsUsed()) {
            $result .= "Defender used his core defence and the following skills " . implode(',', $round->getDefence()->getSkills()) . PHP_EOL;
        }

        $result .= "The attacker {$round->getAttackerName()} did a total damage of {$round->getTotalDamage()} to {$round->getDefenderName()}" . PHP_EOL;

        if($round->getDefenderRemainingHealth()) {
            $result .= "Remaining health at the end of the round for {$round->getDefenderName()}(Defender) was {$round->getDefenderRemainingHealth() }" . PHP_EOL;
        } else {
            $result .= "Defender {$round->getDefenderName()} has fallen, defeated by {$round->getAttackerName()}" . PHP_EOL;
        }

        $result .= self::ROW_SEPARATOR . PHP_EOL;
        return $result;
    }

    private function displayInitialStats(array $initialCharacterStats)
    {
        echo "Let the battle between the following " . implode(' vs. ',array_keys($initialCharacterStats)) . " begin" . PHP_EOL;
        foreach ($initialCharacterStats as $characterAsArray) {
            foreach ($characterAsArray as $key => $value) {
                echo strtoupper($key) . " => {$value}" . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }
}