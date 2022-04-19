<?php

namespace App\Csv;

use App\Interfaces\csv\ImportResultInterface;

class ImportResult implements ImportResultInterface
{
    private array $persons;
    private array $projects;
    private array $donations;
    private array $rewards;

    public function __construct(
        array $persons,
        array $projects,
        array $donations,
        array $rewards,
    ) {
        $this->persons = $persons;
        $this->projects = $projects;
        $this->donations = $donations;
        $this->rewards = $rewards;
    }

    public function getPersons(): iterable
    {
        return $this->persons;
    }

    public function countPersons(): int
    {
        return \count([$this->persons]);
    }

    public function getProjects(): iterable
    {
        return $this->projects;
    }

    public function countProjects(): int
    {
        return \count([$this->projects]);
    }

    public function getDonations(): iterable
    {
        return $this->donations;
    }

    public function countDonations(): int
    {
        return \count([$this->getDonations()]);
    }

    public function getRewards(): iterable
    {
        return $this->rewards;
    }

    public function countRewards(): int
    {
        return \count([$this->getRewards()]);
    }

}