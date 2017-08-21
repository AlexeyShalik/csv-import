<?php

namespace AppBundle\Service\RulesService;

interface RulesServiceInterface
{
    /**
     * Executes rule fit process.
     *
     * @param $row
     */
    public function process(array $row);
}
