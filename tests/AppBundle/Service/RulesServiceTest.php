<?php

namespace AppBundle\Tests;

use AppBundle\Service\RulesService\RulesService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class ProductFilterTest extends TestCase
{
    private $aggregator;
    
    public function setUp()
    {
        $this->aggregator = new RulesService($this->getContainer());
    }

    protected function getContainer()
    {
        $emMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $emMock;
    }
    
    public function ruleFitsProvider()
    {
        return [
            [
                true, [
                    'Product Code' => 'P0001',
                    'Product Name' => 'TV',
                    'Product Description' => '32” Tv',
                    'Stock' => 10,
                    'Cost in GBP' => 399.99]
            ]
        ];
    }
    
    /**
     * @dataProvider ruleFitsProvider
     *
     * @param $result
     * @param $input
     */
    public function testRuleFits($result, $input)
    {
        $converter = $this->aggregator->ruleFits($input);
        $this->assertEquals($result, $converter);
    }
}
