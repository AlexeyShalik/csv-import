<?php

namespace AppBundle\Service\RulesService;

use Symfony\Component\DependencyInjection\Container;


class RulesService implements RulesServiceInterface
{
    const MINIMAL_COST = 5;
    const MAXIMAL_COST = 1000;
    const MINIMAL_STOCK = 10;

    private $error;
    private $success;    
    private $container;
    private $keys;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->error = [];
        $this->success = [];
        $this->keys = ['null'];
    }

    public function process($row)
    {
        ($this->ruleFits($row))? array_push($this->success, $row) : array_push($this->error, $row);
        array_push($this->keys, $row['Product Code']);
    }

    public function ruleFits($row)
    {
        $conditionA = floatval($row['Cost in GBP']) < self::MINIMAL_COST && intval($row['Stock']) < self::MINIMAL_STOCK;
        $conditionB = floatval($row['Cost in GBP']) > self::MAXIMAL_COST;
        $conditionC = array_search($row['Product Code'], $this->keys);
        $falseCondition = ($conditionA || $conditionB) || $conditionC;
        return !$falseCondition;
    }

    public function getError()
    {
        return $this->error;
    }
    
    public function getSuccess()
    {
        return $this->success;
    }
}