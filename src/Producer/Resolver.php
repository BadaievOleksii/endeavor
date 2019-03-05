<?php

namespace Endeavor\Producer;

use Endeavor\Core\TaskInterface;

/**
 * Class Resolver
 */
class Resolver implements DestinationResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveQueueName(TaskInterface $task)
    {
        return get_class($task);
    }
}
