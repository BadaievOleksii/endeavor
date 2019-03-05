<?php


namespace Endeavor\Tests\Consumer;

use Endeavor\Consumer\Extension\ChainedExtension;
use Endeavor\Consumer\Extension\ExitOnIdleExtension;
use Endeavor\Consumer\TaskConsumer;
use Endeavor\Tasks\ConverterTask;
use Endeavor\Tests\Consumer\Fixtures\ExceptionExtension;
use Endeavor\Tests\Consumer\Fixtures\ExitOnPostConsumeExtension;
use Endeavor\Tests\Tasks\Fixtures\NullTask;
use Mockery;
use phpDocumentor\Reflection\Types\This;


/**
 * Class TaskConsumerTest
 *
 * @use php vendor/bin/codecept run unit Consumer/TaskConsumerTest
 */
class TaskConsumerTest extends \Codeception\TestCase\Test
{
    
    private $amqpQueueMock;
    
    private $amqpMessageMock;
    
    private $amqpConsumerMock;
    
    private $amqpContextMock;
    
    private $amqpConnectionFactoryMock;
    
    protected function createAmqpMocks()
    {
        $task = new NullTask();
        
        $this->amqpQueueMock = Mockery::instanceMock(\Interop\Amqp\AmqpQueue::class);
        $this->amqpQueueMock->shouldReceive('setFlags')->withArgs([ \Interop\Amqp\AmqpQueue::FLAG_DURABLE ]);
        $this->amqpQueueMock->shouldReceive('getQueueName')->andReturn('');
        
        $this->amqpMessageMock = Mockery::instanceMock(\Interop\Amqp\AmqpMessage::class);
        $this->amqpMessageMock->shouldReceive('getHeaders')->andReturn([]);
        $this->amqpMessageMock->shouldReceive('getProperties')->andReturn('');
        $this->amqpMessageMock->shouldReceive('getBody')->andReturn(serialize($task));
        
        $this->amqpConsumerMock = Mockery::instanceMock(\Interop\Amqp\AmqpConsumer::class);
        $this->amqpConsumerMock->shouldReceive('receiveNoWait')->andReturn($this->amqpMessageMock);
        $this->amqpConsumerMock->shouldReceive('acknowledge')->withArgs([ $this->amqpMessageMock ]);
        
        $this->amqpContextMock = Mockery::instanceMock(\Interop\Amqp\AmqpContext::class);
        $this->amqpContextMock->shouldReceive('close')->once();
        $this->amqpContextMock->shouldReceive('createQueue')->once()->andReturn($this->amqpQueueMock);
        $this->amqpContextMock->shouldReceive('declareQueue')->once()->withArgs([ $this->amqpQueueMock ]);
        $this->amqpContextMock->shouldReceive('createConsumer')
                              ->once()
                              ->withArgs([ $this->amqpQueueMock ])
                              ->andReturn($this->amqpConsumerMock);
        
        /** @var mixed $amqpConnectionFactoryMock */
        $this->amqpConnectionFactoryMock = Mockery::instanceMock(\Enqueue\AmqpBunny\AmqpConnectionFactory::class);
        $this->amqpConnectionFactoryMock->shouldReceive('createContext')->andReturn($this->amqpContextMock);
    }
    
    public function testRunWithReceivedMessage()
    {
        $this->createAmqpMocks();
        
        $consumer = new TaskConsumer(
            $this->amqpConnectionFactoryMock,
            new ChainedExtension([ new ExitOnPostConsumeExtension() ])
        );
        
        $consumer->run('test');
    }
    
    public function testRunWithoutMessage()
    {
        $this->createAmqpMocks();
    
        $this->amqpConsumerMock = Mockery::instanceMock(\Interop\Amqp\AmqpConsumer::class);
        $this->amqpConsumerMock->shouldReceive('receiveNoWait')->andReturn(null);
    
        $this->amqpContextMock = Mockery::instanceMock(\Interop\Amqp\AmqpContext::class);
        $this->amqpContextMock->shouldReceive('close')->once();
        $this->amqpContextMock->shouldReceive('createQueue')->once()->andReturn($this->amqpQueueMock);
        $this->amqpContextMock->shouldReceive('declareQueue')->once()->withArgs([ $this->amqpQueueMock ]);
        $this->amqpContextMock->shouldReceive('createConsumer')
                              ->once()
                              ->withArgs([ $this->amqpQueueMock ])
                              ->andReturn($this->amqpConsumerMock);
    
    
        $this->amqpConnectionFactoryMock = Mockery::instanceMock(\Enqueue\AmqpBunny\AmqpConnectionFactory::class);
        $this->amqpConnectionFactoryMock->shouldReceive('createContext')->andReturn($this->amqpContextMock);
        
        $consumer = new TaskConsumer(
            $this->amqpConnectionFactoryMock,
            new ChainedExtension([ new ExitOnIdleExtension() ])
        );
    
        $consumer->run('test');
    }
    
    public function testRunWithException()
    {
        $this->createAmqpMocks();
        
        $consumer = new TaskConsumer(
            $this->amqpConnectionFactoryMock,
            new ChainedExtension([ new ExceptionExtension() ])
        );
    
        $consumer->run('test');
    }
    
}
