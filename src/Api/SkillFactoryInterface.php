<?php
declare(strict_types=1);

namespace App\Api;

use App\Domain\Skill\AbstractSkill;

interface SkillFactoryInterface
{
    public function create(string $skillIdentifier, float $chance, int $priority): AbstractSkill;
}