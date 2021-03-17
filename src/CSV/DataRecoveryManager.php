<?php


namespace App\CSV;


use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\CSV\DataRecoveryManagerInterface;
use App\Repository\DonationRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Repository\RewardRepository;
use Doctrine\ORM\EntityManagerInterface;

class DataRecoveryManager implements DataRecoveryManagerInterface
{
    private EntityManagerInterface $entityManager;
    private PersonRepository $personRepository;
    private ProjectRepository $projectRepository;
    private RewardRepository $rewardRepository;
    private DonationRepository $donationRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                PersonRepository $personRepository,
                                ProjectRepository $projectRepository,
                                RewardRepository $rewardRepository,
                                DonationRepository $donationRepository)
    {
        $this->entityManager = $entityManager;
        $this->personRepository = $personRepository;
        $this->projectRepository = $projectRepository;
        $this->rewardRepository = $rewardRepository;
        $this->donationRepository = $donationRepository;
    }


    public function personRecover(string $firstName, string $lastName): Person
    {
        $person = $this->personRepository
            ->findOneBy([
                'firstName' => \ucfirst(\trim($firstName)),
                'lastName' => \ucfirst(\trim($lastName)),
            ]);

        if (null === $person) {
            $person = new Person(
                \ucfirst(\trim($firstName)),
                \ucfirst(\trim($lastName))
            );

            $this->entityManager->persist($person);
        }

        return $person;
    }

    public function projectRecover(string $projectName): Project
    {
        $project = $this->projectRepository->findOneBy(['name' => \trim($projectName)]);

        if (null === $project) {
            $project = new Project($projectName);
            $this->entityManager->persist($project);
        }

        return $project;
    }

    public function rewardRecover(string $rewardName, mixed $rewardQuantity, Project $project): Reward
    {
        $reward = $this->rewardRepository
            ->findOneBy(['name' => \trim($rewardName)]);

        if (null === $reward) {
            if (\is_int($rewardQuantity)) {
                $reward = new Reward(\trim($rewardName), $rewardQuantity, $project);
            } else {
                $reward = new Reward(\trim($rewardName), (int) ($rewardQuantity), $project);
            }
            $this->entityManager->persist($reward);
        } else {
            $reward->setProject($project);
            $reward->setQuantity($rewardQuantity);
        }

        return $reward;
    }

    public function donationRecover(int $amount, Person $person, Reward $reward): void
    {
        $donation = $this->donationRepository->findOneBy(['amount' => $amount]);

        if (null === $donation) {
            if (\is_int($amount)) {
                $donation = new Donation($amount, $person, $reward);
            } else {
                $donation = new Donation( (int) ($amount), $person, $reward);
            }
            $this->entityManager->persist($donation);
        }else{
            $donation->setPerson($person);
            $donation->setReward($reward);
        }
    }
}