<?php

namespace Endeavor\Workers;

use Symfony\Component\Process\Process;

/**
 * Class ProcessExecutor
 * Executed shell command in separate process using Symfony\Process
 *
 * @see \Symfony\Component\Process\Process
 */
class ProcessExecutor implements ExecutorInterface
{
    /**
     * Process instance
     *
     * @var Process
     */
    protected $process;

    /**
     * ProcessExecutor constructor
     * Disables output for the instance of Symfony/Process and sets the timeout (max execution time)
     *
     * @param string $cmd Shell command to execute
     * @param int $timeout Timeout of process in seconds, defaults to 6 hours (60 * 60 * 6)
     */
    public function __construct($cmd, $timeout = 21600)
    {
        $this->process = new Process($cmd);
        $this->process->disableOutput();
        $this->process->setTimeout($timeout);
    }

    /**
     * Runs command (async)
     *
     * @return bool if command started
     */
    public function execute()
    {
        $this->process->start();

        return $this->process->isRunning();
    }

    /**
     * {@inheritdoc}
     */
    public function wait()
    {
        return (bool)$this->process->wait();
    }
}
