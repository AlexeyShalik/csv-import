<?php

namespace AppBundle\Service\ImportWorkflow;


class ImportWorkflow implements ImportWorkflowInterface
{
    public function process()
    {
        
    }

    public function getSuccessCount()
    {
        return 10;
    }
    
    public function getTotalRowsCount()
    {
        return 15;
    }
}