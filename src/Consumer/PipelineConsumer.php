<?php

namespace Endeavor\Consumer;

use Endeavor\Producer\PipelineProducer;
use Endeavor\Tasks\PipeTask;
use Endeavor\Tasks\TaskPipeline;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class PipelineConsumer
 *
 */
class PipelineConsumer extends TaskConsumer
{
    use ContainerAwareTrait;

    /**
     * @var TaskPipeline
     */
    protected $pipeline;

    /**
     * @var int
     */
    protected $stageId;

    /**
     * @param string $pipelineId
     * @param int $stageId
     */
    public function runPipelineStage($pipelineId, $stageId)
    {
        $this->pipeline = $this->container->get($pipelineId);
        $this->stageId = $stageId;


        $this->pipeline->seek($stageId);
        /** @var PipeTask $task */
        $task = $this->pipeline->current();
        $this->run($task->stageId);
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(Runtime $r)
    {
        $processorClass = $this->resolver->resolveProcessor($r->task);
        /** @var \Endeavor\Core\TaskProcessorInterface $processor */
        $processor = $this->container->get($processorClass, Container::NULL_ON_INVALID_REFERENCE) ?: new $processorClass();

        $this->pipeline->rewind();
        $this->pipeline->seek($this->stageId);
        $this->pipeline->next();

        $this->log->debug("Getting next task from pipeline", [$this->pipeline]);
        /** @var \Endeavor\Tasks\PipeTask $nextTask */
        $nextTask = $this->pipeline->current();
        $nextTaskName = get_class($nextTask);
        $this->log->debug("[$r->taskName] ==> [$nextTaskName]");

        $taskProducer = new PipelineProducer($this->connectionFactory, $this->log);

        foreach ($processor->process($r->task) as $oneResult) {
            if ($nextTask instanceof PipeTask) {
                $nextTask->input($oneResult);
                $taskProducer->send($nextTask);
            }
        }

        $this->log->notice("[{$r->taskName}] processed", [json_encode($r)]);
    }
}
