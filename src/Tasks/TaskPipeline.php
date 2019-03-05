<?php

namespace Endeavor\Tasks;

use ArrayIterator;

/**
 * Class TaskPipeline
 *
 *
 * Pipeline - an array of tasks, that should be executed one after another, and result of one task is then provided
 * to another as an input. Consumers and producers use iterate/rewind to select needed stage of pipeline,
 * and send/receive proper task
 *
 * @see \Endeavor\Tasks\PipeTask
 * @see https://www.enterpriseintegrationpatterns.com/patterns/messaging/PipesAndFilters.html
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/RoutingTable.html
 */
class TaskPipeline extends ArrayIterator
{
    /**
     * @var string
     */
    protected $pipelineId;
    
    /**
     * TaskPipeline constructor.
     *
     * @param string $pipelineId
     * @param \Endeavor\Tasks\PipeTask[] $stages
     */
    public function __construct($pipelineId, $stages)
    {
        parent::__construct($stages);
        
        $this->pipelineId = $pipelineId;
        $this->setupStages();
    }
    
    /**
     *
     */
    private function setupStages()
    {
        foreach ($this as $stageId => $stageTask) {
            if (!$stageTask instanceof PipeTask) {
                throw new \InvalidArgumentException(TaskPipeline::class . ' should receive ' . PipeTask::class . '[]');
            }
            $stageTask->pipelineId = $this->pipelineId;
            $stageTask->stageId = "{$this->pipelineId}.stage{$stageId}";
        }
    }
    
}