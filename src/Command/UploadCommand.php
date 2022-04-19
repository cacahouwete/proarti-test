<?php

namespace App\Command;

use App\Interfaces\csv\CsvManagerInterface;
use SplFileObject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:csv-load',
    description: 'Upload file of csv data',
)]
class UploadCommand extends Command
{
    private CsvManagerInterface $csvManagerInterface;

    public function __construct(
        CsvManagerInterface $csvManagerInterface
    ) {
        parent::__construct();
        $this->csvManagerInterface = $csvManagerInterface;
    }

    protected function configure(): void
    {
        $this->setDescription('Uploads a csv file');
        $this->setHelp('This command allows you to import a csv file');
        $this->addArgument('csv_file_path', InputArgument::REQUIRED, 'csv file path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->csvManagerInterface->import(new SplFileObject($input->getArgument('csv_file_path')));

        $output->writeln('File uploaded');
        $output->writeln('Nb Person: '.$result->countPersons());
        $output->writeln('Nb Project: '.$result->countProjects());
        $output->writeln('Nb Donation: '.$result->countDonations());
        $output->writeln('Nb Reward: '.$result->countRewards());

        return Command::SUCCESS;
    }
}
