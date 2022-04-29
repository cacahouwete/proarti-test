<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ImportDataService;

class CsvService extends CsvEncoder
{
    public function __construct(private ImportDataService $dataService, private EntityManagerInterface $entityManager)
    {
    }
    public function persistDataCsv($rows)
    {
        foreach ($rows as $row) {
            $person = $this->dataService->importPerson($row['last_name'], $row['first_name']);
            $project = $this->dataService->importProject($row['project_name']);
            $reward = $this->dataService->importReward($row['reward'], $row['reward_quantity'], $project);
            $this->dataService->importDonation($row['amount'], $person, $reward);

            $this->entityManager->flush();
        }
    }

    public function getDataCsv($path)
    {
        return $this->decode(file_get_contents($path), 'csv', [CsvEncoder::DELIMITER_KEY => ';']);
    }
}