<?php
declare(strict_types=1);

namespace App\Tests\Domain\Dto;

use App\Domain\DTO\Action;
use App\Domain\DTO\Round;
use App\Exception\InvalidRoundConstructorParamsException;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    private const ATTACKER_NAME = 'Orderus';
    private const DEFENDER_NAME = 'Beast';
    private const ROUND_NUMBER = 1;
    private const DEFENDER_REMAINING_HEALTH = 10;

    private const ATTACK_VALUE = 40;
    private const DEFENCE_VALUE = 20;

    private Action $attack;
    private Action $defence;
    private Round $round;

    public function setUp(): void
    {
        $this->attack = new Action(self::ATTACK_VALUE, []);
        $this->defence = new Action(self::DEFENCE_VALUE,['MagicShield']);
        $this->round = new Round(
            self::ROUND_NUMBER,
            self::ATTACKER_NAME,
            self::DEFENDER_NAME,
            false,
            20,
            $this->attack,
            $this->defence
        );
    }

    public function testConstructorAndGetters()
    {
        $this->assertEquals(self::ROUND_NUMBER, $this->round->getRoundNumber());
        $this->assertEquals(self::ATTACKER_NAME, $this->round->getAttackerName());
        $this->assertEquals(self::DEFENDER_NAME, $this->round->getDefenderName());
        $this->assertFalse($this->round->wasDefenderLucky());
        $this->assertEquals(self::ATTACK_VALUE, $this->round->getAttack()->getValue());
        $this->assertCount(0, $this->round->getAttack()->getSkills());
        $this->assertEquals(self::DEFENCE_VALUE, $this->round->getDefence()->getValue());
        $this->assertCount(1, $this->round->getDefence()->getSkills());
    }

    public function testGetTotalDamage()
    {
        $this->assertEquals(self::ATTACK_VALUE - self::DEFENCE_VALUE, $this->round->getTotalDamage());

        $attack = new Action(10, []);
        $defence = new Action(20, []);

        $roundWithZeroDamage = new Round(
            self::ROUND_NUMBER,
            self::ATTACKER_NAME,
            self::DEFENDER_NAME,
            false,
            20,
            $attack,
            $defence
        );

        $this->assertEquals(0, $roundWithZeroDamage->getTotalDamage());

        // Defender evaded the attack
        $roundWithLuckyDefender = $roundWithZeroDamage = new Round(
            self::ROUND_NUMBER,
            self::ATTACKER_NAME,
            self::DEFENDER_NAME,
            true,
            20,
            $attack,
            $defence
        );
        $this->assertEquals(0, $roundWithLuckyDefender->getTotalDamage());
    }

    public function testCreateWithMissingAttack()
    {
        $this->expectException(InvalidRoundConstructorParamsException::class);
        $roundWithNoAction = new Round(
            self::ROUND_NUMBER,
            self::ATTACKER_NAME,
            self::DEFENDER_NAME,
            false,
            20,
            $this->attack,
            null
        );
    }

    public function testCreateWithMissingDefence()
    {
        $this->expectException(InvalidRoundConstructorParamsException::class);
        $roundWithNoAction = new Round(
            self::ROUND_NUMBER,
            self::ATTACKER_NAME,
            self::DEFENDER_NAME,
            false,
            20,
            null,
            $this->defence
        );
    }

    public function testCreateWithMissingActions()
    {
        $this->expectException(InvalidRoundConstructorParamsException::class);
        $roundWithNoAction = new Round(
            self::ROUND_NUMBER,
            self::ATTACKER_NAME,
            self::DEFENDER_NAME,
            false,
            20,
            null,
            null
        );
    }
}