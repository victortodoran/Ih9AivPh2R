<?php
declare(strict_types=1);

namespace App\Tests\Domain\Skill;

use App\Domain\Skill\AbstractDefenceSkill;
use App\Domain\Skill\MagicShield;
use App\Tests\Service\MockChanceCalculatorAlwaysTrue;
use PHPUnit\Framework\TestCase;

class MagicShieldTest extends TestCase
{
    private const CHANCE = 20;
    private const PRIORITY = 20;
    private MagicShield $magicShieldSkill;

    protected function setUp(): void
    {
        $this->magicShieldSkill = new MagicShield(
            new MockChanceCalculatorAlwaysTrue(),
            self::CHANCE,
            self::PRIORITY
        );
    }

    public function testConstructorAndGetters(): void
    {
        $this->assertEquals(self::PRIORITY, $this->magicShieldSkill->getPriority());
        $this->assertEquals(self::CHANCE, $this->magicShieldSkill->getChance());
        $this->assertEquals(AbstractDefenceSkill::SKILL_TYPE, $this->magicShieldSkill->getType());
    }

    public function testApplySkill(): void
    {
        $defence = $this->magicShieldSkill->applySkill(50, 100);
        $this->assertEquals(100, $defence);

        $defence = $this->magicShieldSkill->applySkill(50, 15);
        $this->assertEquals(57.5, $defence);
    }
}