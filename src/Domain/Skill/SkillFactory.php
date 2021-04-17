<?php
declare(strict_types=1);

namespace App\Domain\Skill;

use App\Api\SkillFactoryInterface;
use App\Exception\InvalidSkillValuesException;
use App\Exception\UnknownSkillException;
use App\Service\ChanceCalculator;

class SkillFactory implements SkillFactoryInterface
{
    private ChanceCalculator $chanceCalculator;

    public function __construct(
        ChanceCalculator $chanceCalculator
    ) {
        $this->chanceCalculator = $chanceCalculator;
    }

    private const SKILLS = [
        RapidStrike::IDENTIFIER => RapidStrike::class,
        MagicShield::IDENTIFIER => MagicShield::class
    ];

    /**
     * @throws UnknownSkillException
     * @throws InvalidSkillValuesException (see AbstractSkill::validateConstructorData())
     */
    public function create(string $skillIdentifier, float $chance, int $priority): AbstractSkill
    {
        if(!isset(self::SKILLS[$skillIdentifier])) {
            throw new UnknownSkillException("There is no such skill with given skill identifier '{$skillIdentifier}'");
        }

        $skillClass = self::SKILLS[$skillIdentifier];
        return new $skillClass($this->chanceCalculator,$chance, $priority);
    }
}