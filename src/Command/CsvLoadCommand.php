<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Person;
use App\Entity\Donation;
use App\Entity\Reward;
use App\Entity\Projet;

#[AsCommand(
    name: 'app:csv-load',
    description: 'Load a csv data file',
)]
class CsvLoadCommand extends Command
{
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'File to load')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if ($file) {
            $io->note(sprintf('You passed a file: %s', $file));

            $rows = [];

            if (($fstream = fopen($file, "r")))
            {
                $i = 0;
                while (($data = fgetcsv($fstream, null, ";")))
                {
                    $i++;
                    $rows[] = $data;
                }

                fclose($fstream);
            }

            // Read rows
            foreach ($rows as $line)
            {
                $person = new Person();
                $person->setFirstname($line[0]);
                $person->setLastname($line[1]);

                $projet = new Projet();
                $projet->setName($line[3]);

                $donation = new Donation();
                $donation->setAmount(floatval($line[2]));
                $donation->setPerson($person);

                $person->addDonation($donation);

                $reward = new Reward();
                $reward->setReward($line[4]);
                $reward->setRewardQuantity(intval($line[5]));
                $reward->setProjet($projet);

                $donation->setReward($reward);

                $projet->addReward($reward);

                $this->entityManager->persist($person);
                $this->entityManager->persist($projet);
                $this->entityManager->persist($donation);
                $this->entityManager->persist($reward);

                $this->entityManager->flush();
            }

            $io->success('Data successfully loaded!');
        }


        return Command::SUCCESS;
    }
}
