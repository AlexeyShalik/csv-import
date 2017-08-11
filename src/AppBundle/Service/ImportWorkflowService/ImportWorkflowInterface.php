<?php

namespace AppBundle\Service\ImportWorkflowService;

interface ImportWorkflowInterface
{
    public function process();

    public function getSuccessCount();

    public function getTotalRowsCount();

    public function getError();
}