<?php

namespace Endeavor\Tasks;

use Endeavor\Core\TaskProcessorInterface;

/**
 * Class SoapTaskProcessor
 *
 */
class SoapTaskProcessor implements TaskProcessorInterface
{
    /**
     * @param \Endeavor\Tasks\SoapTask $task
     *
     * @return array
     */
    public function process($task)
    {
        $soapClient = new \SoapClient($task->wsdl, $task->options);
        
        $result = $soapClient
            ->{$task->method}($task->data);
            
        return ($result) ? [ (object) $result ] : [];
    }
}
