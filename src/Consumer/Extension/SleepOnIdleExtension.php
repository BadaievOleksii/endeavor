<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Class SleepOnIdleExtension
 *
 */
class SleepOnIdleExtension implements ExtensionInterface
{
    use NullExtensionTrait;

    const ONE_MINUTE_SLEEP = 60000000;

    /**
     * @var int $sleepMicroseconds microseconds to sleep
     */
    protected $sleepMicroseconds;

    /**
     * SleepOnIdleExtension constructor.
     *
     * @param int $sleepMicroseconds
     */
    public function __construct($sleepMicroseconds = 1000000)
    {
        $this->sleepMicroseconds = $sleepMicroseconds;
    }

    /**
     * {@inheritdoc}
     */
    public function onIdle(Runtime $runtime)
    {
        usleep($this->sleepMicroseconds);
    }

}
