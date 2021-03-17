<?php

namespace App\CSV;

use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\CSV\DataRecoveryManagerInterface;
use App\Interfaces\CSV\ImportResultInterface;
use App\Repository\DonationRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Repository\RewardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CsvManager implements CsvManagerInterface
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private PersonRepository $personRepository;
    private DonationRepository $donationRepository;
    private ProjectRepository $projectRepository;
    private RewardRepository $rewardRepository;
    private DataRecoveryManagerInterface $dataRecoveryManagerInterface;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        PersonRepository $personRepository,
        DonationRepository $donationRepository,
        ProjectRepository $projectRepository,
        RewardRepository $rewardRepository,
        DataRecoveryManagerInterface $dataRecoveryManagerInterface
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->personRepository = $personRepository;
        $this->donationRepository = $donationRepository;
        $this->projectRepository = $projectRepository;
        $this->rewardRepository = $rewardRepository;
        $this->dataRecoveryManagerInterface = $dataRecoveryManagerInterface;
    }

    /**
     * @param \SplFileInfo $filePath the CSV File path to import
     *
     * @return ImportResultInterface true or false according to import success/failure
     */
    public function import(\SplFileInfo $filePath): ImportResultInterface
    {
        $persons = $this->personRepository->findAll();
        $donations = $this->donationRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $rewards = $this->rewardRepository->findAll();

        return new ImportResult($persons, $donations, $rewards, $projects);
    }

    public function processDataRecovery(string $filePath): void
    {
        $this->recoverData($this->getDataFromFile($filePath));
    }

    private function recoverData(array $data): void
    {
        foreach ($data as $row) {

            if (isset($row['first_name'], $row['last_name'])) {
                $person = $this->dataRecoveryManagerInterface->personRecover($row['first_name'], $row['last_name']);
            }

            if (isset($row['project_name'])) {
                $project = $this->dataRecoveryManagerInterface->projectRecover($row['project_name']);
            }

            if (isset($row['reward'], $project)) {
                $reward = $this->dataRecoveryManagerInterface->rewardRecover(
                    $row['reward'],
                    $row['reward_quantity'],
                    $project);
            }

            if (isset($row['amount'], $person, $reward)) {
                $this->dataRecoveryManagerInterface->donationRecover($row['amount'], $person, $reward);
            }

            $this->entityManager->flush();
        }
    }

    private function getDataFromFile(string $filePath): array
    {
        /* @var string $fileString */
        $fileString = \file_get_contents($filePath);

        $data = $this->serializer->decode($fileString, 'csv', ['csv_delimiter' => ';']);

        if (\array_key_exists('results', $data)) {
            return $data['results'];
        }

        return $data;
    }
}
