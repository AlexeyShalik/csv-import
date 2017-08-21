<?php

namespace AppBundle\Tests;

use AppBundle\Service\ValidatorService\ValidatorService;
use PHPUnit\Framework\TestCase;

class ValidatorServiceTest extends TestCase
{
    private $aggregator;

    public function setUp()
    {
        $this->aggregator = new ValidatorService();
    }

    public function discontinuedConverterProvider()
    {
        return [
            [true, 'yes'],
            [false, ''],
            [false, 12],
            [false, '12qefsfdg'],
        ];
    }

    /**
     * @dataProvider discontinuedConverterProvider
     *
     * @param $result
     * @param $input
     */
    public function testGetDiscontinuedConverter($result, $input)
    {
        $converter = $this->aggregator->getDiscontinuedConverter($input);
        $condition = !is_null($input) && $converter instanceof \DateTime;
        $this->assertEquals($result, $condition);
    }

    public function costConverterProvider()
    {
        return [
            [
                0, 'abcdef',
                0, '',
                123, '$123',
                123, '123$',
                123, '$123aas$',
                456.78, '456.78',
                12.25, '12.250',
            ],
        ];
    }

    /**
     * @dataProvider costConverterProvider
     *
     * @param $result
     * @param $input
     */
    public function testGetCostConverter($result, $input)
    {
        $converter = $this->aggregator->getCostConverter($input);
        $this->assertEquals($result, $converter);
    }

    public function stockConverterProvider()
    {
        return [
            [null, ''],
            [null, '5aa'],
            [null, 'aa155bcv'],
            [25, '25'],
            [677, '677.577'],
        ];
    }

    /**
     * @dataProvider stockConverterProvider
     *
     * @param $result
     * @param $input
     */
    public function testGetStockConverter($result, $input)
    {
        $converter = $this->aggregator->getStockConverter($input);
        $this->assertEquals($result, $converter);
    }
}
