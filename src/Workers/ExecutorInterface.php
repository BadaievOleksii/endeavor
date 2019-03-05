<?php

namespace Endeavor\Workers;

/**
 * Interface ExecutorInterface
 * Used for different implementations of running shell command
 *
 */
interface ExecutorInterface
{
    /**
     * ExecutorInterface constructor.
     * Accepts shell command to execute
     *
     * @param string $cmd
     */
    public function __construct($cmd);

    /**
     * Starts command
     *
     * @return bool if command executed successfully
     */
    public function execute();

    /**
     * Wait for the command to finish
     *
     * @return bool
     */
    public function wait();
}
