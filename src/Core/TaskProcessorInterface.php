<?php

namespace Endeavor\Core;

/**
 * Interface TaskProcessorInterface
 *
 * Should contain logic of execution for corresponding tasks
 */
interface TaskProcessorInterface
{
    /**
     * @param \Endeavor\Core\TaskInterface $task
     *
     * @return mixed
     */
    public function process($task);
}
