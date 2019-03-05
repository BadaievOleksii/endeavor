<?php

namespace Endeavor\Consumer;

use Endeavor\Core\TaskInterface;

/**
 * Class ClassnameResolver
 *
 * Resolves task to processor simply appending `Processor`, e.g.: FooTask -> FooTaskProcessor
 *
 */
class ClassnameResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveProcessor(TaskInterface $task)
    {
        return get_class($task) . 'Processor';
    }
}
