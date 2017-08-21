<?php

namespace AppBundle\Service\RulesService;

use Symfony\Component\DependencyInjection\Container;

class RulesService implements RulesServiceInterface
{
    const MINIMAL_COST = 5;
    const MAXIMAL_COST = 1000;
    const MINIMAL_STOCK = 10;

    private $errors;
    private $success;
    private $container;
    private $keys;
    private $skipped;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->errors = [];
        $this->success = [];
        $this->keys = ['null'];
        $this->skipped = [];
    }

    /**
     * Executes rule fit process.
     *
     * @param $row
     *
     * @return array or null
     */
    public function process(array $row)
    {
        if ($this->ruleFits($row)) {
            if ($this->getSkippedFilter($row)) {
                array_push($this->success, $row);
                array_push($this->keys, $row[$this->container->getParameter('product_code')]);

                return $row;
            }
        } else {
            array_push($this->skipped, $row);

            return null;
        }
    }

    /**
     * Checks, if the row accepts import rules.
     *
     * @param $row
     *
     * @return bool
     */
    public function ruleFits(array $row) : bool
    {
        $conditionA = floatval($row[$this->container->getParameter('cost_in_GBP')]) < self::MINIMAL_COST && intval($row[$this->container->getParameter('stock')]) < self::MINIMAL_STOCK;
        $conditionB = floatval($row[$this->container->getParameter('cost_in_GBP')]) > self::MAXIMAL_COST;
        $falseCondition = $conditionA || $conditionB;

        return !$falseCondition;
    }

    /**
     * Helper method. Checks if 'Product Code' is duplicate.
     *
     * @param $row
     *
     * @return bool
     */
    public function getSkippedFilter(array $row) : bool
    {
        if (array_search($row[$this->container->getParameter('product_code')], $this->keys)) {
            array_push($this->errors, $row);

            return false;
        } else {
            return true;
        }
    }

    /**
     * @return array
     */
    public function getSkipped() : array
    {
        return $this->skipped;
    }

    /**
     * @return array
     */
    public function getError() : array
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function getSuccess() : int
    {
        return count($this->success);
    }
}
