<?php


namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class LoggerExtension
 *
 */
class LoggerExtension implements ExtensionInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * LoggerExtension constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function onStart(Runtime $runtime)
    {
        $this->logger->notice('Starting', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onPreConsume(Runtime $runtime)
    {
        $this->logger->debug('(PreConsume)', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onPreProcess(Runtime $runtime)
    {
        $this->logger->info('(PreProcess)', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onPostProcess(Runtime $runtime)
    {
        $this->logger->info('(PostProcess)', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onPostConsume(Runtime $runtime)
    {
        $this->logger->debug('(PostConsume)', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onIdle(Runtime $runtime)
    {
        $this->logger->debug('(IDLE)', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onInterrupted(Runtime $runtime)
    {
        $this->logger->error('Consumer interrupted', $runtime->jsonSerialize());
    }


    /**
     * {@inheritdoc}
     */
    public function onFinish(Runtime $runtime)
    {
        $this->logger->notice('Stopped', $runtime->jsonSerialize());
    }
}
