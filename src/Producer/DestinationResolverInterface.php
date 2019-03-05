<?php

namespace Endeavor\Producer;

use Endeavor\Core\TaskInterface;

/**
 * Interface DestinationResolverInterface
 *
 * Determines what queue name impl to use for a certain Task
 *
 *
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/DynamicRouter.html
 */
interface DestinationResolverInterface
{
    /**
     * @param \Endeavor\Core\TaskInterface $task
     *
     * @return string $task Classname of appropriate queue name for this task
     */
    public function resolveQueueName(TaskInterface $task);
}
