<?php
declare(strict_types=1);

namespace App\Tests\Domain\Skill;

use App\Domain\Skill\AbstractAttackSkill;
use App\Domain\Skill\RapidStrike;
use App\Tests\Service\MockChanceCalculatorAlwaysTrue;
use PHPUnit\Framework\TestCase;

class RapidStrikeTest extends TestCase
{
    private const CHANCE = 20;
    private const PRIORITY = 20;
    private RapidStrike $rapidStrikeSkill;

    protected function setUp(): void
    {
        $this->rapidStrikeSkill = new RapidStrike(
            new MockChanceCalculatorAlwaysTrue(),
            self::CHANCE,
            self::PRIORITY
        );
    }

    public function testConstructorAndGetters(): void
    {
        $this->assertEquals(self::PRIORITY, $this->rapidStrikeSkill->getPriority());
        $this->assertEquals(self::CHANCE, $this->rapidStrikeSkill->getChance());
        $this->assertEquals(AbstractAttackSkill::SKILL_TYPE, $this->rapidStrikeSkill->getType());
    }

    public function testApplySkill(): void
    {
        $attack = $this->rapidStrikeSkill->applySkill(50);
        $this->assertEquals(100, $attack);

        $attack = $this->rapidStrikeSkill->applySkill(35);
        $this->assertEquals(70, $attack);

        $attack = $this->rapidStrikeSkill->applySkill(0);
        $this->assertEquals(0, $attack);
    }
}