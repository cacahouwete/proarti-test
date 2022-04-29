<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\CsvService;

#[AsCommand(
    name: 'app:csv-load',
    description: 'Load CSV File in a database',
)]
class LoadCsvCommand extends Command
{
    private $csvService;

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
        parent::__construct();
    }

    protected function configure() :void
    {
        $this->addArgument('csv_path', InputArgument::REQUIRED, 'Enter CSV file path');
    }

    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('csv_path');

        // If the path does not exist return an error
        if (!file_exists($path))
		{
			$io->error('File not found.');
			return Command::FAILURE;
		}

        $rows = $this->csvService->getDataCsv($path);
        $this->csvService->persistDataCsv($rows);

        $io->success('CSV data loaded to Database');
        $io->table($rows, []);

        return Command::SUCCESS;
    }
}