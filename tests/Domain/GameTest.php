<?php
declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Character;
use App\Domain\DTO\Round;
use App\Domain\Game;
use App\Domain\InMemoryCharacterFactory;
use App\Domain\Skill\SkillFactory;
use App\Exception\GameOverException;
use App\Service\ChanceCalculator;
use App\Tests\Service\MockChanceCalculatorAlwaysFalse;
use App\Tests\Service\MockChanceCalculatorAlwaysTrue;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private const HEALTH = 10000;
    private const STRENGTH = 50;
    private const DEFENCE = 50;

    private Game $gameWithAlwaysLucky;
    private Game $gameWithAlwaysUnlucky;

    public function setUp(): void
    {
        $characterFactory = new InMemoryCharacterFactory(
            new SkillFactory(new MockChanceCalculatorAlwaysTrue())
        );
        [$characterOne, $characterTwo]  = $characterFactory->createCharacters();
        $this->gameWithAlwaysLucky = new Game(
            new MockChanceCalculatorAlwaysTrue(),
            $characterOne,
            $characterTwo,
            20
        );

        $this->gameWithAlwaysUnlucky = new Game(
            new MockChanceCalculatorAlwaysFalse(),
            clone $characterOne,
            clone $characterTwo,
            20
        );
    }

    public function testRoundWithEvasion()
    {
        $round = $this->gameWithAlwaysLucky->executeRound();
        $this->assertInstanceOf(Round::class, $round);
        $this->assertTrue($round->wasDefenderLucky());
    }

    public function testRoundWithNoEvasion()
    {
        $round = $this->gameWithAlwaysUnlucky->executeRound();
        $this->assertInstanceOf(Round::class, $round);
        $this->assertFalse($round->wasDefenderLucky());
    }

    public function testCharacterOrderDetermination()
    {
        // Test first attacker determination by speed
        $characterOne = $this->getCharacter(10, 20);
        $characterTwo = $this->getCharacter(5, 30);

        $game = new Game(
            new ChanceCalculator(),
            $characterOne,
            $characterTwo,
            20
        );

        $firstRound = $game->executeRound();
        $this->assertNotEquals($characterOne->getName(), $characterTwo->getName());
        $this->assertEquals($characterOne->getName(), $firstRound->getAttackerName());
        $this->assertEquals($characterTwo->getName(), $firstRound->getDefenderName());

        // Test first attacker determination by luck
        $characterTwo = $this->getCharacter(10, 30);

        $game = new Game(
            new ChanceCalculator(),
            $characterOne,
            $characterTwo,
            20
        );

        $firstRound = $game->executeRound();
        $this->assertNotEquals($characterOne->getName(), $characterTwo->getName());
        $this->assertEquals($characterTwo->getName(), $firstRound->getAttackerName());
        $this->assertEquals($characterOne->getName(), $firstRound->getDefenderName());
    }

    public function testMaximumNumberOfRounds()
    {
        // Stats are built in such a manner that neither character can die in 5 rounds
        foreach (range(1,5) as $roundNo) {
            $characterOne = $this->getCharacter(10, 20);
            $characterTwo = $this->getCharacter(5, 30);

            $game = new Game(
                new ChanceCalculator(),
                $characterOne,
                $characterTwo,
                $roundNo
            );

            while($game->isGameOver() === false) {
                $game->executeRound();
            }
            $this->assertEquals($roundNo, $game->getNumberOfPlayedRounds());
        }
    }

    public function testGameOverException()
    {
        $this->expectException(GameOverException::class);

        $characterOne = $this->getCharacter(10, 20);
        $characterTwo = $this->getCharacter(5, 30);
        $game = new Game(
            new ChanceCalculator(),
            $characterOne,
            $characterTwo,
            1
        );

        while($game->isGameOver() === false) {
            $game->executeRound();
        }
        $game->executeRound();
    }

    private function getCharacter(float $speed, float $luck): Character
    {
        return new Character(
            sha1((string)(time() + rand(1,100))),
            self::HEALTH,
            self::STRENGTH,
            self::DEFENCE,
            $speed,
            $luck
        );
    }
}