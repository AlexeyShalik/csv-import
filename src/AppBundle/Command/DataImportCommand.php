<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;

class DataImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('csv-import')
            ->setDescription('A simple console command that imports data into mysql database')
            ->setHelp('You may execute csv-import!')
        ;
        $this->addArgument('filename', InputArgument::REQUIRED, 'Specify the file you want to import');
        $this->addOption('test-mode', 'test', InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Welcome to database data importer!');
        $io->section('Importing '.$input->getArgument('filename').' into the database...');

        $importService = $this->getContainer()->get('import_workflow');
        $importService->initialize($input->getArgument('filename'));
        $importService->setTestMode($input->getOption('test-mode'));

        try {
            $importService->process();
            $io->newLine();
            $io->success('Done!');
            $io->section('Successfully imported '.$importService->getSuccessCount().' of '.$importService->getTotalRowsCount().' rows');
            $errors = $importService->getError();
            $skipped = $importService->getSkipped();
            $this->logResults('Rows, which are not accepted according to import rules %2$s %1$d %3$s', $skipped, $io)
                ->logResults('Rows, which duplicate %2$s %1$d %3$s', $errors, $io);

            return 0;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return 1;
        }
    }

    /**
     * @param string $message
     * @param array $data
     * @param SymfonyStyle $io
     * @return DataImportCommand
     */
    protected function logResults(string $message, array $data, SymfonyStyle $io) : DataImportCommand
    {
        $io->text(sprintf($message, count($data), '[', ']: '));
        foreach ($data as $row) {
            $io->text('Product Code: '.$row['Product Code']);
        }
        $io->newLine();

        return $this;
    }
}
