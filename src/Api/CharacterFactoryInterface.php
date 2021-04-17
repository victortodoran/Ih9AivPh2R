<?php

namespace App\Api;

use App\Domain\Character;

interface CharacterFactoryInterface
{
    /**
     * @return Character[] array
     */
    public function createCharacters(): array;
}