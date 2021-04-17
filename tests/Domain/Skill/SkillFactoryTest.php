<?php
declare(strict_types=1);

namespace App\Tests\Domain\Skill;

use App\Domain\Skill\MagicShield;
use App\Domain\Skill\RapidStrike;
use App\Domain\Skill\SkillFactory;
use App\Service\ChanceCalculator;
use App\Tests\Service\MockChanceCalculatorAlwaysTrue;
use PHPUnit\Framework\TestCase;

class SkillFactoryTest extends TestCase
{
    private SkillFactory $skillFactory;

    public function setUp(): void
    {
        $chanceCalculator = new MockChanceCalculatorAlwaysTrue();
        $this->skillFactory = new SkillFactory($chanceCalculator);
    }

    public function testCreate()
    {
        $rapidStrikeSkill = $this->skillFactory->create(RapidStrike::IDENTIFIER, 10, 20);
        $this->assertInstanceOf(RapidStrike::class, $rapidStrikeSkill);

        $magicShieldSkill = $this->skillFactory->create(MagicShield::IDENTIFIER, 10, 20);
        $this->assertInstanceOf(MagicShield::class, $magicShieldSkill);
    }
}