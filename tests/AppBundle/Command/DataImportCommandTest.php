<?php

namespace tests\AppBundle\Command;

use AppBundle\Command\DataImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\SplFileInfo;

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

    public function testFileExists()
    {
        $input = [
            'command' => 'csv-import',
            'filename' => __DIR__.'/../stock.csv',
            '--test-mode' => true,
        ];
        $this->assertFileExists($input['filename']);
    }

    public function testCheckingTheFileFormat()
    {
        $input = [
            'command' => 'csv-import',
            'filename' => __DIR__.'/../stock.csv',
            '--test-mode' => true,
        ];
        $info = new SplFileInfo($input['filename'], null, null);
        $this->assertEquals('csv', $info->getExtension());
    }
}
