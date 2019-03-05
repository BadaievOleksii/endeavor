<?php

namespace Endeavor\Consumer;

use Endeavor\Core\TaskInterface;

/**
 * Interface ProcessorResolverInterface
 *
 * Determines what Processor impl to use for a certain Task
 *
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/DynamicRouter.html
 */
interface ResolverInterface
{
    /**
     * @param TaskInterface $task
     *
     * @return string classname of appropriate processor for this task
     */
    public function resolveProcessor(TaskInterface $task);
    
}
