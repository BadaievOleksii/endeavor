<?php

namespace Endeavor\Workers;

/**
 * Class NullExecutor
 * Mock-like implementation of ExecutorInterface
 *
 */
class NullExecutor implements ExecutorInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct($cmd)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function wait()
    {
        return true;
    }
}
