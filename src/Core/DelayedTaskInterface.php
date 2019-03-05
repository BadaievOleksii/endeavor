<?php

namespace Endeavor\Core;

/**
 * Interface DelayedTaskInterface
 *
 */
interface DelayedTaskInterface extends TaskInterface
{
    /**
     * @return int Time to Live for Task in Queue
     */
    public function getTTL();
}
