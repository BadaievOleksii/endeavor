<?php

namespace Endeavor\Consumer;

use Endeavor\Util\JSON;
use JsonSerializable;

/**
 * Class Runtime
 * "Consumer runtime" represents current state (logger, amqp objects,
 *  task-related objects, ...) of Consumer
 *
 */
class Runtime implements JsonSerializable
{
    /**
     * @var string
     */
    public $taskName;

    /**
     * @var bool
     */
    public $isRunning;

    /**
     * @var \Interop\Amqp\AmqpContext
     */
    public $amqpContext;

    /**
     * @var \Interop\Amqp\AmqpQueue
     */
    public $amqpQueue;

    /**
     * @var \Interop\Amqp\AmqpConsumer
     */
    public $amqpConsumer;

    /**
     * @var \Interop\Amqp\AmqpMessage
     */
    public $amqpMessage;

    /**
     * @var \Endeavor\Core\TaskInterface
     */
    public $task;

    /**
     * @var mixed
     */
    public $taskResult;

    /**
     * @var \Exception
     */
    public $exception;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $result = [
            'running' => $this->isRunning,
            'task' => [
                'name' => $this->taskName,
            ],
        ];

        if ($this->task) {
            $result['task']['class'] = get_class($this->task);
            $result['task']['data'] = JSON::encode($this->task);
        }

        if ($this->taskResult) {
            $result['task']['result'] = JSON::encode($this->taskResult);
        }

        if ($this->exception) {
            $result['exception'] = [
                'class' => get_class($this->exception),
                'message' => $this->exception->getMessage(),
                'trace' => $this->exception->getTraceAsString(),
            ];
        }

        return $result;
    }
}
