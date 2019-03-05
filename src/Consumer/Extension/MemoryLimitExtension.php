<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Class MemoryLimitExtension
 *
 */
class MemoryLimitExtension implements ExtensionInterface
{
    use NullExtensionTrait;

    /**
     * @var int memory limit (bytes)
     */
    protected $memoryLimit;

    /**
     * @param int $memoryLimit in MBs
     */
    public function __construct($memoryLimit = 100)
    {
        if (false == is_int($memoryLimit)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected memory limit is int but got: "%s"',
                is_object($memoryLimit) ? get_class($memoryLimit) : gettype($memoryLimit)
            ));
        }
        $this->memoryLimit = $memoryLimit * 1024 * 1024;
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
     * Gets current memory and compares to threshold
     *
     * @return bool
     */
    protected function shouldBeStopped()
    {
        $memoryUsage = memory_get_usage(true);

        return ($memoryUsage >= $this->memoryLimit);
    }

}
