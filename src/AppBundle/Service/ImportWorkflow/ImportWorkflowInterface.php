<?php

namespace AppBundle\Service\ImportWorkflow;

interface ImportWorkflowInterface
{
    public function process();

    public function getSuccessCount();

    public function getTotalRowsCount();
}