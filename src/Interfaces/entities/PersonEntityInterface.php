<?php

namespace App\Interfaces\entities;

use App\Entity\Person;

interface PersonEntityInterface
{
    public function findByFirstAndLastName(string $firstName, string $lastName): Person;

    public function persist(Person $person): void;

    public function persistAndFlush(Person $person): void;

    public function findById(int $id): Person;

}