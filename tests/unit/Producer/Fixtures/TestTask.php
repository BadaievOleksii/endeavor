<?php

namespace Endeavor\Tests\Producer\Fixtures;

use Endeavor\Core\DelayedTaskInterface;
use Endeavor\Core\TaskInterface;

/**
 * Class Task
 */
class TestTask implements TaskInterface, DelayedTaskInterface
{

    /**
     * @return int Time to Live for Task in Queue
     */
    public function getTTL()
    {
        return -1;
    }
}
