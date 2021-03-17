<?php


namespace App\Interfaces\CSV;


use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;

interface DataRecoveryManagerInterface
{
    public function personRecover(string $firstName, string $lastName): Person;

    public function projectRecover(string $projectName): Project;

    public function rewardRecover(string $rewardName, object $rewardQuantity, Project $project): Reward;

    public function donationRecover(int $amount, Person $person, Reward $reward): void;

}