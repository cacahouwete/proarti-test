<?php

namespace App\Csv;

use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\csv\ImportDataMangerInterface;
use App\Interfaces\entities\DonationEntityInterface;
use App\Interfaces\entities\PersonEntityInterface;
use App\Interfaces\entities\ProjectEntityInterface;
use App\Interfaces\entities\RewardEntityInterface;


class ImportDataManager implements ImportDataMangerInterface
{
    private PersonEntityInterface $personEntityInterface;
    private ProjectEntityInterface $projectEntityInterface;
    private RewardEntityInterface $rewardEntityInterface;
    private DonationEntityInterface $donationEntityInterface;

    public function __construct(
        PersonEntityInterface $personEntityInterface,
        ProjectEntityInterface $projectEntityInterface,
        RewardEntityInterface $rewardEntityInterface,
        DonationEntityInterface $donationEntityInterface
    ) {
        $this->personEntityInterface = $personEntityInterface;
        $this->projectEntityInterface = $projectEntityInterface;
        $this->rewardEntityInterface = $rewardEntityInterface;
        $this->donationEntityInterface = $donationEntityInterface;
    }

    public function importPerson(string $firstName, string $lastName): Person
    {
        $firstNameValue = \ucfirst(\trim($firstName));
        $lastNameValue = \ucfirst(\trim($lastName));

        try {
            return $this->personEntityInterface->findByFirstAndLastName($firstNameValue, $lastNameValue);
        } catch (\Exception $e) {
            $person = new Person(
                $firstNameValue,
                $lastNameValue,
            );

            $this->personEntityInterface->persist($person);

            return $person;
        }
    }

    public function importProject(string $projectName): Project
    {
        $projectNameValue = \trim($projectName);
        try {
            return $this->projectEntityInterface->findByName($projectNameValue);
        } catch (\Exception $e) {
            $project = new Project($projectNameValue);
            $this->projectEntityInterface->persist($project);

            return $project;
        }
    }

    public function importReward(string $rewardName, int $rewardQuantity, Project $project): Reward
    {
        $projectNameValue = \trim($rewardName);

        try {
            $reward = $this->rewardEntityInterface->findByName($projectNameValue);
            $reward->setProject($project);
            $reward->setQuantity($rewardQuantity);

            return $reward;
        } catch (\Exception $e) {
            $reward = new Reward($projectNameValue, $rewardQuantity, $project);
            $this->rewardEntityInterface->persist($reward);

            return $reward;
        }
    }

    public function importDonation(int $amount, Person $person, Reward $reward): Donation
    {
        $donation = new Donation($amount, $person, $reward);
        $this->donationEntityInterface->persist($donation);

        return $donation;
    }
}