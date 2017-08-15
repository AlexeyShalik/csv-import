<?php

namespace AppBundle\Tests;

use AppBundle\Service\ImportWorkflowService\ImportWorkflow;
use AppBundle\Service\ValidatorService\ValidatorService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class ProductWorkflowTest extends TestCase
{
    private $aggregator;
    private $validator;
    public function setUp()
    {
        $this->aggregator = new ImportWorkflow($this->getEntityManager(), $this->getContainer());
        $this->validator = new ValidatorService();
    }
    
    protected function getEntityManager()
    {
        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $emMock;
    }

    protected function getContainer()
    {
        $emMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $emMock;
    }
    
    public function testProcess()
    {
        $this->aggregator->setTestMode(true);
        $this->aggregator->initialize(__DIR__.'/../stock.csv');
        $result = 24;
        $this->assertEquals($this->aggregator->process()->getSuccessCount(), $result);
    }
}
