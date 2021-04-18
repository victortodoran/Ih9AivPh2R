<?php
require __DIR__ . '/vendor/autoload.php';

use App\Domain\Game;
use App\Domain\InMemoryCharacterFactory;
use App\Domain\Skill\SkillFactory;
use App\Service\ChanceCalculator;
use App\Ui\Console;

const MAX_NUMBER_OF_ROUNDS = 20;
try {
    $ui = new Console();
    $chanceCalculator = new ChanceCalculator();
    $skillFactory = new SkillFactory(new ChanceCalculator());
    $characterFactory = new InMemoryCharacterFactory($skillFactory);
    [$characterOne, $characterTwo]  = $characterFactory->createCharacters();
    $game = new Game($chanceCalculator, $characterOne, $characterTwo, MAX_NUMBER_OF_ROUNDS);
    $ui->execute($game);
} catch (\Exception $exception) {
    echo "Oops. Something went wrong during the game." . PHP_EOL;
}

