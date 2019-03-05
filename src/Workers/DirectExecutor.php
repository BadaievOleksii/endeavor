<?php

namespace Endeavor\Workers;

/**
 * Class DirectExecutor
 * Executes shell command straight-away with system()
 *
 */
class DirectExecutor implements ExecutorInterface
{
    /**
     * Shell command to execute
     *
     * @var string
     */
    protected $cmd;


    /**
     * ExecutorInterface constructor.
     * Accepts shell command to execute
     *
     * @param string $cmd
     */
    public function __construct($cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return system($this->cmd) === '';
    }

    /**
     * {@inheritdoc}
     */
    public function wait()
    {
        return true;
    }
}
