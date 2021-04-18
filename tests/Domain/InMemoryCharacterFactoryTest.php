<?php
declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Character;
use App\Domain\InMemoryCharacterFactory;
use App\Domain\Skill\SkillFactory;
use App\Service\ChanceCalculator;
use PHPUnit\Framework\TestCase;

class InMemoryCharacterFactoryTest extends TestCase
{
    private InMemoryCharacterFactory $characterFactory;

    public function setUp(): void
    {
        $skillFactory = new SkillFactory(new ChanceCalculator());
        $this->characterFactory = new InMemoryCharacterFactory($skillFactory);
    }

    public function testCreate()
    {
        $characters = $this->characterFactory->createCharacters();
        $this->assertCount(2, $characters);
        foreach ($characters as $character) {
            $this->assertInstanceOf(Character::class, $character);
        }
    }
}