<?php

namespace Endeavor\Tests\Tasks\Fixtures;

use Endeavor\Core\TaskProcessorInterface;


/**
 * Class NullTaskProcessor
 *
 */
class NullTaskProcessor implements TaskProcessorInterface
{
    
    /**
     * {@inheritdoc}
     */
    public function process($task)
    {
        return [];
    }
}
