<?php
require __DIR__ . '/vendor/autoload.php';

use App\Domain\InMemoryCharacterFactory;

try {
    $ui = new \App\Ui\Console();
    $skillFactory = new \App\Domain\Skill\SkillFactory();
    $characterFactory = new InMemoryCharacterFactory($skillFactory);
    $game = new \App\Domain\Game(... $characterFactory->createCharacters());
    $game->execute();
    $ui->execute($game);
} catch (\Exception $exception) {
    echo "Oops. Something went wrong during the game." . PHP_EOL;
}

