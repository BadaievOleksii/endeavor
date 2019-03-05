<?php

namespace Endeavor\Tests\Producer;

use Mockery;


/**
 * Class TaskProducerTest
 *
 * @use php vendor/bin/codecept run unit Producer/TaskProducerTest
 */
class TaskProducerTest extends \Codeception\TestCase\Test
{
    private $amqpQueueMock;
    
    private $amqpMessageMock;
    
    private $amqpProducerMock;
    
    private $amqpContextMock;
    
    private $amqpConnectionFactoryMock;
    
    protected function createAmqpMocks()
    {
        
        $this->amqpQueueMock = Mockery::instanceMock(\Interop\Amqp\AmqpQueue::class);
        $this->amqpQueueMock->shouldReceive('setFlags')->withArgs([ \Interop\Amqp\AmqpQueue::FLAG_DURABLE ]);
        $this->amqpQueueMock->shouldReceive('getQueueName')->andReturn('');
        
        $this->amqpMessageMock = Mockery::instanceMock(\Interop\Amqp\AmqpMessage::class);
        $this->amqpMessageMock->shouldReceive('setFlags')->withArgs([ \Interop\Amqp\AmqpMessage::FLAG_MANDATORY ]);
        $this->amqpMessageMock->shouldReceive('setMessageId');
        $this->amqpMessageMock->shouldReceive('setTimestamp');
        $this->amqpMessageMock->shouldReceive('getBody')->andReturn('');
        
        $this->amqpProducerMock = Mockery::instanceMock(\Interop\Amqp\AmqpProducer::class);
        $this->amqpProducerMock->shouldReceive('send')->withArgs([ $this->amqpQueueMock, $this->amqpMessageMock ]);
        
        $this->amqpContextMock = Mockery::instanceMock(\Interop\Amqp\AmqpContext::class);
        $this->amqpContextMock->shouldReceive('close')->once();
        $this->amqpContextMock->shouldReceive('createQueue')->once()->andReturn($this->amqpQueueMock);
        $this->amqpContextMock->shouldReceive('declareQueue')->once()->withArgs([ $this->amqpQueueMock ]);
        $this->amqpContextMock->shouldReceive('createMessage')->once()->andReturn($this->amqpMessageMock);
        $this->amqpContextMock->shouldReceive('createProducer')->once()->andReturn($this->amqpProducerMock);
        
        /** @var mixed $amqpConnectionFactoryMock */
        $this->amqpConnectionFactoryMock = Mockery::instanceMock(\Enqueue\AmqpBunny\AmqpConnectionFactory::class);
        $this->amqpConnectionFactoryMock->shouldReceive('createContext')->andReturn($this->amqpContextMock);
    }
    
    /**
     * @throws \Interop\Queue\Exception
     */
    public function testSend()
    {
        $this->createAmqpMocks();
        
        $taskMock = Mockery::instanceMock(\Endeavor\Core\TaskInterface::class);
        
        $producer = new \Endeavor\Producer\TaskProducer($this->amqpConnectionFactoryMock);
        $producer->send($taskMock);
    }
    
}
