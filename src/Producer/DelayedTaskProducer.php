<?php

namespace Endeavor\Producer;

use Endeavor\Core\DelayedTaskInterface;
use Endeavor\Core\TaskInterface;
use Interop\Amqp\AmqpMessage;
use Interop\Amqp\AmqpQueue;


/**
 * Class DelayedTaskProducer
 *
 * Extends TaskProducer with functionality, needed for delayed task
 *
 */
class DelayedTaskProducer extends TaskProducer
{

    /**
     * @param TaskInterface|DelayedTaskInterface $task
     * @throws \Interop\Queue\Exception
     * @throws \Interop\Queue\InvalidDestinationException
     * @throws \Interop\Queue\InvalidMessageException
     */
    public function send(TaskInterface $task)
    {
        $queue = $this->context->createQueue($this->getDestinationRoute($task));
        $queue->setFlags(AmqpQueue::FLAG_DURABLE);
        $queue->setArguments($this->getDelayedQueueArguments($task));
        $this->context->declareQueue($queue);

        $message = $this->context->createMessage(serialize($task));
        $message->setFlags(AmqpMessage::FLAG_MANDATORY);

        $message->setMessageId(hash('md5', $message->getBody()));
        $message->setTimestamp(time());

        $producer = $this->context->createProducer();
        $producer->send($queue, $message);
        $this->log->info('Sent new delayed task by route: [' . $queue->getQueueName() . ']', [$message]);
    }

    /**
     * {@inheritdoc}
     *
     * Verifies that received task is of class DelayedTask and if true adds prefix '.delayed' to queue
     */
    protected function getDestinationRoute(TaskInterface $task)
    {
        if (!$task instanceof DelayedTaskInterface) {
            throw new \InvalidArgumentException('Delayed task producer can only work with tasks of interface DelayedTask');
        }

        return parent::getDestinationRoute($task) . '.delayed';
    }

    /**
     * @param DelayedTaskInterface $task
     * @return array
     */
    private function getDelayedQueueArguments($task)
    {
        return [
            'x-dead-letter-exchange' => '',
            'x-message-ttl' => $task->getTTL(),
            'x-dead-letter-routing-key' => parent::getDestinationRoute($task),
        ];
    }

}
