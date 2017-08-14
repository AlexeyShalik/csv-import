<?php
namespace Tests\AppBundle\Command;

use AppBundle\Command\DataImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DataImportCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $app = new Application($kernel);
        $app->add(new DataImportCommand());
        $command = $app->find('csv-import');
        $input = [
            'command' => 'csv-import',
            'filename' => __DIR__.'/../stock.csv',
            '--test-mode' => true,
        ];
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);
        $output = $commandTester->getDisplay();
        $this->assertContains('imported 24 of 29 rows', $output);
    }
}