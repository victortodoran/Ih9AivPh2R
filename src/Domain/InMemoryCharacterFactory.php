<?php
declare(strict_types=1);


namespace App\Domain;


use App\Api\CharacterFactoryInterface;
use App\Api\SkillFactoryInterface;
use App\Domain\Skill\AbstractSkill;

class InMemoryCharacterFactory implements CharacterFactoryInterface
{
    private SkillFactoryInterface $skillFactory;

    public function __construct(
        SkillFactoryInterface $skillFactory
    ) {
        $this->skillFactory = $skillFactory;
    }

    private const NAME = 'name';
    private const HEALTH = 'health';
    private const STRENGTH = 'strength';
    private const DEFENCE = 'defence';
    private const SPEED = 'speed';
    private const LUCK = 'luck';
    private const SKILLS = 'skills';

    private const SKILL_IDENTIFIER = 'skill_identifier';
    private const SKILL_TYPE = 'skill_type';
    private const SKILL_CHANCE = 'skill_chance';

    private const CHARACTERS_INFORMATION = [
        'orderus' => [
            self::NAME      => 'Orderus',
            self::HEALTH    => ['min' => 70, 'max' => 100],
            self::STRENGTH  => ['min' => 70, 'max' => 80],
            self::DEFENCE   => ['min' => 45, 'max' => 55],
            self::SPEED     => ['min' => 40, 'max' => 50],
            self::LUCK      => ['min' => 10, 'max' => 30],
            self::SKILLS    => [
                [
                    self::SKILL_IDENTIFIER => 'rapid_strike',
                    self::SKILL_CHANCE => 10
                ],
                [
                    self::SKILL_IDENTIFIER => 'magic_shield',
                    self::SKILL_CHANCE => 20
                ]
            ]
        ],
        'beast' => [
            self::NAME      =>  'Beast',
            self::HEALTH    =>  ['min' => 60, 'max' => 90],
            self::STRENGTH  =>  ['min' => 60, 'max' => 90],
            self::DEFENCE   =>  ['min' => 40, 'max' => 60],
            self::SPEED     =>  ['min' => 40, 'max' => 60],
            self::LUCK      =>  ['min' => 25, 'max' => 40],
            self::SKILLS    =>  []
        ]
    ];

    public function createCharacters(): array
    {
        $result = [];
        foreach (self::CHARACTERS_INFORMATION as $characterInformation) {
            $character = new Character(
                $characterInformation['name'],
                (float) rand(... array_values($characterInformation[self::HEALTH])),
                (float) rand(... array_values($characterInformation[self::STRENGTH])),
                (float) rand(... array_values($characterInformation[self::DEFENCE])),
                (float) rand(... array_values($characterInformation[self::SPEED])),
                (float) rand(... array_values($characterInformation[self::LUCK])),
            );
            foreach($characterInformation[self::SKILLS] as $skill) {
                $character->addSkill(
                    $this->skillFactory->create(... array_values($skill))
                );
            }
        }

        return $result;
    }

}