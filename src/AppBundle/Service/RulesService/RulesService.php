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
     * @return $this
     */
    public function process($row)
    {
        if ($this->ruleFits($row)) {
            if ($this->getSkippedFilter($row)) {
                array_push($this->success, $row);
            }
        } else {
            array_push($this->skipped, $row);
        }
       
        array_push($this->keys, $row['Product Code']);
        
        return $this;
    }

    /**
     * Checks, if the row accepts import rules.
     *
     * @param $row
     *
     * @return bool
     */
    public function ruleFits($row)
    {
        $conditionA = floatval($row['Cost in GBP']) < self::MINIMAL_COST && intval($row['Stock']) < self::MINIMAL_STOCK;
        $conditionB = floatval($row['Cost in GBP']) > self::MAXIMAL_COST;
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
    public function getSkippedFilter($row)
    {
        if (array_search($row['Product Code'], $this->keys)) {
            array_push($this->errors, $row);
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return array
     */
    public function getSkipped()
    {
        return $this->skipped;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getSuccess()
    {
        return $this->success;
    }
}
