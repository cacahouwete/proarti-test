<?php

namespace App\Interfaces\entities;

use App\Entity\Project;

interface ProjectEntityInterface
{
    public function findByName(string $name): Project;

    public function persist(Project $project): void;

    public function persistAndFlush(Project $project): void;

}