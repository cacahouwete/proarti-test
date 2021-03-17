<?php

namespace App\Command;

use App\Interfaces\CSV\CsvManagerInterface;
use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UploadCSVCommand extends Command
{
    protected static $defaultName = 'app:upload-csv';
    protected CsvManagerInterface $upload;

    public function __construct(
        CsvManagerInterface $upload,
    ) {
        parent::__construct();
        $this->upload = $upload;
    }

    protected function configure(): void
    {
        $this->setDescription('Uploads a csv file');

        $this->setHelp('This command allows you to import a csv file');

        $this->addArgument('csv_file_path', InputArgument::REQUIRED, 'csv file path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->upload->processDataRecovery($input->getArgument('csv_file_path'));

        $result = $this->upload->import(new SplFileObject($input->getArgument('csv_file_path')));

        $output->writeln('File uploaded');
        $output->writeln('Nb Person: '.$result->countPersons());
        $output->writeln('Nb Project: '.$result->countProjects());
        $output->writeln('Nb Donation: '.$result->countDonations());

        return Command::SUCCESS;
    }
}
