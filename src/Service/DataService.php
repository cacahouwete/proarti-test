<?php
namespace App\Service;

use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use Doctrine\ORM\EntityManagerInterface;

class DataService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function setAllData($rows)
    {
        foreach ($rows as $row) {
            $person = new Person();
            $donation = new Donation();
            $reward = new Reward();
            $project = new Project();
    
            $person
                ->setFirstName(ucfirst(trim($row['first_name'])))
                ->setLastName(ucfirst(trim($row['last_name'])));

            $donation
                ->setAmount($row['amount'])
                ->setPerson($person)
                ->setReward($reward);

            $reward
                ->setReward($row['reward'])
                ->setRewardQuantity($row['reward_quantity'])
                ->setProject($project);

            $project
                ->setProjectName($row['project_name']);

            $this->entityManager->persist($person);
            $this->entityManager->persist($donation);
            $this->entityManager->persist($reward);
            $this->entityManager->persist($project);

            $this->entityManager->flush();
        }
    }
}