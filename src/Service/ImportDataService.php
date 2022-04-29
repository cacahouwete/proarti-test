<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;

class ImportDataService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function importPerson(string $lastName, string $firstName)
    {
        $person = new Person();
        $person
            ->setFirstName(ucfirst(trim($firstName)))
            ->setLastName(ucfirst(trim($lastName)));
        
        $this->entityManager->persist($person);

        return $person;
    }

    public function importDonation(int $amount, object $person, object $reward)
    {
        $donation = new Donation();
        $donation
            ->setAmount($amount)
            ->setPerson($person)
            ->setReward($reward);
        
        $this->entityManager->persist($donation);

        return $donation;
    }

    public function importReward(string $rewardName, int $rewardQuantity, object $project)
    {
        $reward = new Reward();
        $reward
            ->setReward(ucfirst($rewardName))
            ->setRewardQuantity($rewardQuantity)
            ->setProject($project);
        
        $this->entityManager->persist($reward);

        return $reward;
    }

    public function importProject(string $projectName)
    {
        $project = new Project();
        $project
            ->setProjectName(ucfirst($projectName));
        
        $this->entityManager->persist($project);

        return $project;
    }
}