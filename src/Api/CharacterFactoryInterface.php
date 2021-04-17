<?php
declare(strict_types=1);

namespace App\Api;

use App\Domain\Character;

interface CharacterFactoryInterface
{
    /**
     * @return Character[] array
     */
    public function createCharacters(): array;
}