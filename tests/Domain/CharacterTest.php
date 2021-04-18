<?php
declare(strict_types=1);

namespace App\Tests\Domain;

use App\Api\ChanceCalculatorInterface;
use App\Domain\Character;
use App\Domain\DTO\Action;
use App\Domain\Skill\MagicShield;
use App\Domain\Skill\RapidStrike;
use App\Tests\Service\MockChanceCalculatorAlwaysTrue;
use PHPUnit\Framework\TestCase;

class CharacterTest extends TestCase
{
    private const NAME = 'Yurnero';
    private const HEALTH = 100;
    private const STRENGTH = 50;
    private const DEFENCE = 50;
    private const SPEED = 10;
    private const LUCK = 10;

    private RapidStrike $rapidStrikeSkill;
    private MagicShield $magicShield;
    private ChanceCalculatorInterface $alwaysTrue;

    public function setUp(): void
    {
        $this->alwaysTrue = new MockChanceCalculatorAlwaysTrue();
        $this->rapidStrikeSkill = new RapidStrike(
            $this->alwaysTrue,
            10,
            10
        );
        $this->magicShield = new MagicShield(
          $this->alwaysTrue,
          10,
          10
        );
    }

    public function testConstructorAndGetters()
    {
        $character = $this->getBasicCharacter();
        $this->assertEquals(self::NAME, $character->getName());
        $this->assertEquals(self::HEALTH, $character->getHealth());
        $this->assertEquals(self::STRENGTH, $character->getStrength());
        $this->assertEquals(self::DEFENCE, $character->getDefence());
        $this->assertEquals(self::SPEED, $character->getSpeed());
        $this->assertEquals(self::LUCK, $character->getLuck());
    }

    public function testComputeAttack()
    {
        // Test generic properties (e.g. type of return) and computeAttack with no skill.
        $character = $this->getBasicCharacter();
        $attack = $character->computeAttack();
        $this->assertInstanceOf(Action::class, $attack);
        $this->assertEquals(self::STRENGTH, $attack->getValue());
        $this->assertCount(0, $attack->getSkills());

        // Test computeAttack with RapidStrike skill.
        $character->addAttackSkill($this->rapidStrikeSkill);
        $attack = $character->computeAttack();
        $this->assertEquals(self::STRENGTH * 2, $attack->getValue());
        $this->assertCount(1, $attack->getSkills());

        // Repeat test to ensure RapidStrike skill applies more than once
        $attack = $character->computeAttack();
        $this->assertEquals(self::STRENGTH * 2, $attack->getValue());
        $this->assertCount(1, $attack->getSkills());
    }

    public function testTakeDamage()
    {
        $character = $this->getBasicCharacter();

        // Test generic properties (e.g. type of return) and attack smaller than defence.
        $defence = $character->takeDamage(10);
        $this->assertInstanceOf(Action::class, $defence);
        $this->assertEquals(self::DEFENCE, $defence->getValue());
        $this->assertCount(0, $defence->getSkills());
        $this->assertEquals(self::HEALTH, $character->getHealth());

        // Test attack equal to defence
        $defence = $character->takeDamage(self::DEFENCE);
        $this->assertEquals(self::DEFENCE, $defence->getValue());
        $this->assertEquals(self::HEALTH, $character->getHealth());

        // Test attack bigger than defence
        $defence = $character->takeDamage(self::DEFENCE + 1);
        $currentExpectedHealth = self::HEALTH - 1;
        $this->assertEquals($currentExpectedHealth, $character->getHealth());

        // Test MagicShield. Health does not decrease if attack <= defence + attack/2
        $character->addDefenceSkill($this->magicShield);
        $defence = $character->takeDamage(100);
        $this->assertEquals(self::DEFENCE + 50, $defence->getValue());
        $this->assertEquals($currentExpectedHealth, $character->getHealth());
        $this->assertCount(1, $defence->getSkills());

        // Test MagicShield. Health decreases if attack > defence + attack/2
        $defence = $character->takeDamage(102);
        $this->assertCount(1, $defence->getSkills());
        $this->assertEquals(101, $defence->getValue());
    }

    public function testCharacterDeath()
    {
        $character = $this->getBasicCharacter();
        $character->takeDamage(self::HEALTH + self::DEFENCE);
        $this->assertTrue($character->isCharacterDefeated());
        $this->assertEquals(0, $character->getHealth());
    }

    private function getBasicCharacter(): Character
    {
        return new Character(
            self::NAME,
            self::HEALTH,
            self::STRENGTH,
            self::DEFENCE,
            self::SPEED,
            self::LUCK
        );
    }
}