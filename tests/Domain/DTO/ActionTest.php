<?php
declare(strict_types=1);

namespace App\Tests\Domain\Dto;

use App\Domain\DTO\Action;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    private Action $actionWithoutSkills;
    private Action $actionWithSkills;

    public function setUp(): void
    {
        $this->actionWithoutSkills = new Action(
            10,
            []
        );
        $this->actionWithSkills = new Action(
            10,
            ['RapidStrike']
        );
    }

    public function testActionWithoutSkills()
    {
        $this->assertEquals(10, $this->actionWithoutSkills->getValue());
        $this->assertFalse($this->actionWithoutSkills->wereSkillsUsed());
        $this->assertCount(0, $this->actionWithoutSkills->getSkills());
    }

    public function testActionWithSkills()
    {
        $this->assertEquals(10, $this->actionWithSkills->getValue());
        $this->assertTrue($this->actionWithSkills->wereSkillsUsed());
        $this->assertCount(1, $this->actionWithSkills->getSkills());
    }
}