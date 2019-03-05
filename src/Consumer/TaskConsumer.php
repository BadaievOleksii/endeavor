<?php

namespace Endeavor\Consumer;

use Endeavor\Consumer\Extension\ChainedExtension;
use Endeavor\Consumer\Extension\ExtensionInterface;
use Enqueue\AmqpBunny\AmqpConnectionFactory;
use Interop\Amqp\AmqpQueue;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class TaskConsumer
 *
 * Implements basic consuming logic and task processing
 *
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/PollingConsumer.html
 *
 */
class TaskConsumer
{
    /**
     *
     * @var \Enqueue\AmqpBunny\AmqpConnectionFactory
     */
    protected $connectionFactory;

    /**
     * @var array|\Endeavor\Consumer\Extension\ExtensionInterface
     */
    protected $extension;

    /**
     * @var \Endeavor\Consumer\ResolverInterface
     */
    protected $resolver;

    /**
     * @var string
     */
    protected $taskName;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * TaskReceiver constructor.
     *
     * @param \Enqueue\AmqpBunny\AmqpConnectionFactory $amqpConnectionFactory
     * @param \Endeavor\Consumer\Extension\ExtensionInterface|null $extension
     * @param \Endeavor\Consumer\ResolverInterface|null $resolver
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        AmqpConnectionFactory $amqpConnectionFactory,
        ExtensionInterface $extension = null,
        ResolverInterface $resolver = null,
        LoggerInterface $logger = null
    )
    {
        $this->connectionFactory = $amqpConnectionFactory;
        $this->extension = $extension ?: new ChainedExtension([]);
        $this->resolver = $resolver ?: new ClassnameResolver();
        $this->log = $logger ?: new NullLogger();
    }

    /**
     * Runs consumer's lifecycle for certain task
     *
     * @param string $queueName name of the task to receive and process
     */
    public function run($queueName)
    {
        $r = new Runtime();
        $r->taskName = $queueName;
        $r->isRunning = false;

        $this->start($r);

        $this->consume($r);

        $this->finish($r);
    }

    /**
     * Executed once at the start of consumer's lifecycle
     *
     * @param \Endeavor\Consumer\Runtime $r
     */
    protected function start(Runtime $r)
    {
        $this->extension->onStart($r);

        $r->amqpContext = $this->connectionFactory->createContext();

        $r->amqpQueue = $r->amqpContext->createQueue($r->taskName);
        $r->amqpQueue->setFlags(AmqpQueue::FLAG_DURABLE);
        $r->amqpContext->declareQueue($r->amqpQueue);

        $r->amqpConsumer = $r->amqpContext->createConsumer($r->amqpQueue);
    }

    /**
     * Polling consumer's loop
     *
     * @param \Endeavor\Consumer\Runtime $r
     */
    protected function consume(Runtime $r)
    {
        try {

            $r->isRunning = true;
            do {
                $this->extension->onPreConsume($r);
                $r->amqpMessage = $r->amqpConsumer->receiveNoWait();

                if (!$r->amqpMessage) {
                    $this->extension->onIdle($r);
                    continue;
                }

                $r->task = unserialize($r->amqpMessage->getBody());

                $this->extension->onPreProcess($r);
                $this->processTask($r);
                $this->extension->onPostProcess($r);

                $r->amqpConsumer->acknowledge($r->amqpMessage);
                $r->amqpMessage = null;
                $r->task = null;
                $r->taskResult = null;
                $this->extension->onPostConsume($r);
            } while ($r->isRunning);

        } catch (\Exception $exception) {
            $r->exception = $exception;
            $this->extension->onInterrupted($r);
        }
    }

    /**
     * Executed once, when consumer finishes work - due to exception or the sub-logic in one of extensions
     *
     * @param \Endeavor\Consumer\Runtime $r
     */
    protected function finish(Runtime $r)
    {
        $r->task = null;
        $r->taskResult = null;
        $r->amqpConsumer = null;
        $r->amqpQueue = null;
        $r->amqpContext->close();
        $r->amqpContext = null;
        $r->isRunning = false;

        $this->extension->onFinish($r);
    }


    /**
     * Processes task (message is already received and saved in Runtime)
     *
     * @param \Endeavor\Consumer\Runtime $r
     * @return void
     */
    protected function processTask(Runtime $r)
    {
        $processorClass = $this->resolver->resolveProcessor($r->task);
        /** @var \Endeavor\Core\TaskProcessorInterface $processor */
        $processor = new $processorClass();
        $this->log->debug("TaskProcessor resolved -> [{$processorClass}]");

        $r->taskResult = $processor->process($r->task);
        $this->log->notice("[{$r->taskName}] processed", [json_encode($r)]);
    }

}
