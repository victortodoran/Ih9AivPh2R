<?php
declare(strict_types=1);


namespace App\Ui;

use App\Domain\DTO\Round;
use App\Domain\Game;

/**
 * Class Console used to output everything that happened during a finished game.
 */
class Console
{
    private const ROW_SEPARATOR = "##############################################################################" . PHP_EOL;

    public function execute(Game $game)
    {
        /** @var Round $round */
        foreach ($game->getRounds() as $round)
        {
            $roundMessage = self::ROW_SEPARATOR;
            $roundMessage .= "Round #{$round->getRoundNumber()}. {$round->getAttackerName()}(Attacker) vs {$round->getDefenderName()}(Defender)" . PHP_EOL;
            if($round->wasDefenderLucky()) {
                $roundMessage .= "Defender {$round->getDefenderName()} used all his luck, evaded {$round->getAttackerName()}'s attack and took no damage" . PHP_EOL;
                echo $roundMessage;
                continue;
            }
            $roundMessage .= "{$round->getAttackerName()} had an attack of {$round->getAttack()->getValue()}" . PHP_EOL;
            if(!empty($round->getAttack()->getSkills())) {
                $roundMessage .= "Attacker used his core strength and the following skills " . implode(',', $round->getAttack()->getSkills()) . PHP_EOL;
            }

            $roundMessage .= "{$round->getDefenderName()} had a defence of {$round->getDefence()->getValue()}" . PHP_EOL;
            if(!empty($round->getDefence()->getSkills())) {
                $roundMessage .= "Defender used his core defence and the following skills " . implode(',', $round->getAttack()->getSkills()) . PHP_EOL;
            }

            $totalDamage = $round->getAttack()->getValue() - $round->getDefence()->getValue() ? : 0;
            $roundMessage .= "The attacker {$round->getAttack()->getValue()} did a total damage of {$totalDamage} to {$round->getDefenderName()}" . PHP_EOL;
            $roundMessage .= "Remaining health at the end of the round for{$round->getDefenderName()}(Defender)" . PHP_EOL;
            $roundMessage .= self::ROW_SEPARATOR;

            echo $roundMessage;
        }
    }
}