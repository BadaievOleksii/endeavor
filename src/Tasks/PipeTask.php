<?php

namespace Endeavor\Tasks;

use Endeavor\Core\TaskInterface;

/**
 * Class PipeTask
 *
 * Represents _one_ task, that is used as a part of pipeline
 */
abstract class PipeTask implements TaskInterface
{
    /**
     * @var string
     */
    public $pipelineId;
    
    /**
     * @var string
     */
    public $stageId;
    
    /**
     * @param mixed $inputData
     *
     * @return void
     */
    abstract public function input($inputData);
    
}