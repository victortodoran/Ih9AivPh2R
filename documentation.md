# A FEW WORDS ABOUT SKILLS
###Order in which skills are applied matters
Given the MagicShield skill's description there are two ways to go abt it.
 - Alter the 'candidate' damage in the current attack. 
 - Increase the defence value of the character to decrease the impact of the 'candidate' damage.

For opinionated reasons the implementation follows the second option.

This however comes with a danger. Even tough in this iteration this is not the case, it is highly likely
that business requirements will introduce multiple skills that impact the defence value of a character.
Case in which the reasons for which the order counts become apparent. 
See the following examples for more details:
- If a there is a new candidate skill, Elvish Gauntlet, that adds 10 to defence, this will not have a negative
impact in the current implementation.
- If there is a new candidate skill, Fairy Necklace, that adds 10% to defence, it is imperative that is
applied before the Magic Shield seeing that if the Magic Shield is applied first, due to the nature of the
Fairy Necklace skill, this will result in an unwanted increase in defence.
  
The order is important for attack skills as well. Let us take the RapidStrike skill. If there are
any other skills that increase the attack value of a given character, given the current implementation,
they must be applied before RapidStrike is applied.

