<?php

namespace App\Csv;

use App\DTO\DTOImportData;
use App\Interfaces\csv\CsvManagerInterface;
use App\Interfaces\csv\ImportDataMangerInterface;
use App\Interfaces\csv\ImportResultInterface;
use App\Repository\DonationRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Repository\RewardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvManager implements CsvManagerInterface
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private PersonRepository $personRepository;
    private DonationRepository $donationRepository;
    private ProjectRepository $projectRepository;
    private RewardRepository $rewardRepository;
    private ImportDataMangerInterface $importDataMangerInterface;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        PersonRepository $personRepository,
        DonationRepository $donationRepository,
        ProjectRepository $projectRepository,
        RewardRepository $rewardRepository,
        ImportDataMangerInterface $importDataMangerInterface,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->personRepository = $personRepository;
        $this->donationRepository = $donationRepository;
        $this->projectRepository = $projectRepository;
        $this->rewardRepository = $rewardRepository;
        $this->importDataMangerInterface = $importDataMangerInterface;
        $this->validator = $validator;
    }

    public function import(\SplFileInfo $filePath): ImportResultInterface
    {
        $errorCollectionTable = $this->importData($this->getDataFromFile($filePath->getPathname()), $this->validator);

        $persons = $this->personRepository->findAll();
        $donations = $this->donationRepository->findAll();
        $rewards = $this->rewardRepository->findAll();
        $projects = $this->projectRepository->findAll();

        return new ImportResult($persons, $donations, $rewards, $projects, $errorCollectionTable);
    }

    private function importData(array $data, ValidatorInterface $validator): iterable
    {
        $errorCollectionTable = [];

        foreach ($data as $key => $row) {
            $rewardQuantity = $row['reward_quantity'];
            $donationAmount = $row['amount'];

            $DTOImportData =
                new DTOImportData(
                    $row['first_name'],
                    $row['last_name'],
                    $row['project_name'],
                    $row['reward'],
                    \is_numeric($rewardQuantity) ? (int) $rewardQuantity : $rewardQuantity,
                    \is_numeric($donationAmount) ? (int) $donationAmount : $donationAmount,
                );

            $errors = $validator->validate($DTOImportData);

            if (\count($errors) > 0) {
                echo "error";
            } else {
                $person = $this->importDataMangerInterface->importPerson(
                    $DTOImportData->firstName,
                    $DTOImportData->lastName
                );

                $project = $this->importDataMangerInterface->importProject($DTOImportData->projectName);

                $reward = $this->importDataMangerInterface->importReward(
                    $DTOImportData->rewardName,
                    $DTOImportData->rewardQuantity,
                    $project);

                $this->importDataMangerInterface->importDonation($DTOImportData->donationAmount, $person, $reward);

                $this->entityManager->flush();
            }
        }

        return $errorCollectionTable;
    }

    private function getDataFromFile(string $filePath): array
    {
        $fileString = \file_get_contents($filePath);

        $data = $this->serializer->decode($fileString, 'csv', ['csv_delimiter' => ';']);

        if (\array_key_exists('results', $data)) {
            return $data['results'];
        }

        return $data;
    }
}