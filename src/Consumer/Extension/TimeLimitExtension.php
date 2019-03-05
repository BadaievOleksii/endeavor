<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Class TimeLimitExtension
 *
 */
class TimeLimitExtension implements ExtensionInterface
{
    use NullExtensionTrait;

    const ONE_HOUR = 3600;

    /**
     * @var int time limit (in seconds)
     */
    protected $timeLimit;

    /**
     * @var int timestamp of consumer start
     */
    protected $startTime;

    /**
     * @param int $timeLimit in seconds
     */
    public function __construct($timeLimit = self::ONE_HOUR)
    {
        $this->timeLimit = $timeLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function onStart(Runtime $runtime)
    {
        $this->startTime = time();
    }

    /**
     * {@inheritdoc}
     */
    public function onPostConsume(Runtime $runtime)
    {
        if ($this->shouldBeStopped()) {
            $runtime->isRunning = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onIdle(Runtime $runtime)
    {
        if ($this->shouldBeStopped()) {
            $runtime->isRunning = false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * It is needed to terminate process with unexpected exitcode and trigger supervisord to autorestart process
     */
    public function onFinish(Runtime $runtime)
    {
        exit(-1);
    }

    /**
     * Gets current timestamp and compares to starting time, if exceeding the initial limit
     *
     * @return bool
     */
    protected function shouldBeStopped()
    {
        return (($this->startTime + $this->timeLimit) <= time());
    }
}