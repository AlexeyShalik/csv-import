<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class DataImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('csv-import')
            ->setDescription('A simple console command that imports data into mysql database')
            ->setHelp('You may execute csv-import!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Welcome to database data importer!');
        
        $importService = $this->getContainer()->get('import_workflow');
       // $io->text();
        try {
            $importService->process();
            $io->newLine();
            $io->success('Done!');
            $io->section('Successfully imported '.$importService->getSuccessCount().' of '.$importService->getTotalRowsCount().' rows');
           // $this->logResults('Rows, which are not accepted according to import rules', $dataLog['skipped'], $io)
          //      ->logResults('Rows, which duplicate or may contain type errors', $dataLog['invalid'], $io);
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }

    protected function logResults($message, $data, $io)
    {

    }
}