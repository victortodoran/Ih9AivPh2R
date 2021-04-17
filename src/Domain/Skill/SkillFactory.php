<?php
declare(strict_types=1);

namespace App\Domain\Skill;

use App\Api\SkillFactoryInterface;
use App\Exception\InvalidSkillValuesException;
use App\Exception\UnknownSkillException;

class SkillFactory implements SkillFactoryInterface
{
    private const SKILLS = [
        RapidStrike::IDENTIFIER => RapidStrike::class,
        MagicShield::IDENTIFIER => MagicShield::class
    ];

    /**
     * @throws UnknownSkillException
     * @throws InvalidSkillValuesException
     */
    public function create(string $skillIdentifier, float $chance): AbstractSkill
    {
        if(!isset(self::SKILLS[$skillIdentifier])) {
            throw new UnknownSkillException("There is no such skill with given skill identifier '{$skillIdentifier}'");
        }

        $skillClass = self::SKILLS[$skillIdentifier];
        return new $skillClass($chance);
    }
}