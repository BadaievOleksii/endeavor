<?php

namespace Endeavor\Producer;

use Endeavor\Core\TaskInterface;
use Enqueue\AmqpBunny\AmqpConnectionFactory;
use Interop\Amqp\AmqpMessage;
use Interop\Amqp\AmqpQueue;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


/**
 * Class TaskProducer
 *
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/DatatypeChannel.html
 */
class TaskProducer
{
    /**
     * @var \Enqueue\AmqpBunny\AmqpConnectionFactory
     */
    protected $connectionFactory;
    
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;
    
    /**
     * @var \Enqueue\AmqpBunny\AmqpContext
     */
    protected $context;

    /**
     * @var \Endeavor\Producer\DestinationResolverInterface
     */
    protected $resolver;

    /**
     * TaskSender constructor.
     *
     * @param \Enqueue\AmqpBunny\AmqpConnectionFactory                         $amqpConnectionFactory
     * @param \Psr\Log\LoggerInterface|null                                    $logger
     * @param \Endeavor\Producer\DestinationResolverInterface|null $resolver
     */
    public function __construct(
        AmqpConnectionFactory $amqpConnectionFactory,
        LoggerInterface $logger = null,
        DestinationResolverInterface $resolver = null
    ){
        $this->connectionFactory = $amqpConnectionFactory;
        $this->log = $logger ?: new NullLogger();
        $this->resolver = $resolver ?: new Resolver();
        
        $this->context = $this->connectionFactory->createContext();
    }
    
    public function __destruct()
    {
        $this->context->close();
    }
    
    /**
     * @param \Endeavor\Core\TaskInterface $task
     *
     * @throws \Interop\Queue\Exception
     */
    public function send(TaskInterface $task)
    {
        $queue = $this->context->createQueue($this->getDestinationRoute($task));
        $queue->setFlags(AmqpQueue::FLAG_DURABLE);
        $this->context->declareQueue($queue);

        $message = $this->context->createMessage(serialize($task));
        $message->setFlags(AmqpMessage::FLAG_MANDATORY);

        $message->setMessageId(hash('md5', $message->getBody()));
        $message->setTimestamp(time());
        
        $producer = $this->context->createProducer();
        $producer->send($queue, $message);
        $this->log->info('Sent new task by route: [' . $queue->getQueueName() . ']', [ $message ]);
    }
    
    /**
     * @param \Endeavor\Core\TaskInterface $task
     *
     * @return string
     */
    protected function getDestinationRoute(TaskInterface $task)
    {
        return $this->resolver->resolveQueueName($task);
    }
}
