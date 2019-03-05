<?php

namespace Endeavor\Producer;

use Endeavor\Core\TaskInterface;
use Endeavor\Tasks\PipeTask;
use Endeavor\Tasks\TaskPipeline;

/**
 * Class PipelineProducer
 *
 * Extends TaskProducer with functionality, needed for pipelines
 *
 */
class PipelineProducer extends TaskProducer
{
    /**
     * Starts pipeline by sending the first task
     *
     * @param \Endeavor\Tasks\TaskPipeline $pipeline
     * @param mixed $pipelineInputData data that will be provided to pipeline's first task (@see \Endeavor\Tasks\PipeTask)
     *
     * @throws \Interop\Queue\Exception
     */
    public function startPipeline(TaskPipeline $pipeline, $pipelineInputData = null)
    {
        $pipeline->rewind();

        /** @var PipeTask $pipelineStart */
        $pipelineStart = $pipeline->current();
        $pipelineStart->input($pipelineInputData);

        $this->send(
            $pipelineStart
        );
    }
    
    /**
     * {@inheritdoc}
     *
     * Verifies that received task is of class PipeTask
     */
    public function getDestinationRoute(TaskInterface $task)
    {
        if (!$task instanceof PipeTask) {
            throw new \InvalidArgumentException('Pipeline producer can only work with tasks of class PipeTask');
        }
        
        /** @var PipeTask $task */
        return $task->stageId;
    }
}
