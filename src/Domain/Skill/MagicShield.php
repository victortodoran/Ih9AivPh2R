<?php
declare(strict_types=1);

namespace App\Domain\Skill;

/**
 * MagicShield skill causes only half of the damage when an enemy attacks
 */
class MagicShield extends AbstractSkill
{
    public const IDENTIFIER = 'magic_shield';

    public function addAttackValue(float $attackValue): float
    {
        return $attackValue;
    }

    /*
     * Given the skill's description there are two ways to go abt this.
     * 1. Alter the 'candidate' damage in the current attack.
     * 2. Increase the defence value of the character to decrease the impact of the candidate damage.
     * For opinionated reasons the implementation follows the second option.
     * This however comes with a danger. Even if in this iteration this is not the case it is highly likely
     * that business requirements will introduce multiple skills that impact the defence value of a character.
     * Depending on the introduced skills the way skills are applied might require changes as it might be possible
     * that skills are applied in a certain order, i.e. each skill will have a priority.
     * Example:
     * A. If a there is a new candidate skill, Elvish Gauntlet, that adds 10 to defence, this will not have a negative
     * impact in the current implementation.
     * B. If there is a new candidate skill, Fairy Necklace, that adds 10% to defence, it is imperative that is
     * applied before the Magic Shield seeing that if the Magic Shield is applied first, due to the nature of the
     * Fairy Necklace skill, this will result in an unwanted increase in defence.
     */
    public function addDefenceValue(float $defenceValue, float $opponentAttackValue): float
    {
        return $defenceValue + $opponentAttackValue / 2;
    }
}