<?php


namespace Endeavor\Tests\Consumer;

use Endeavor\Consumer\Extension\ChainedExtension;
use Endeavor\Consumer\PipelineConsumer;
use Endeavor\Tasks\TaskPipeline;
use Endeavor\Tests\Consumer\Fixtures\ExitOnPostConsumeExtension;
use Endeavor\Tests\Tasks\Fixtures\NullTask;
use Mockery;


/**
 * Class PipelineConsumerTest
 *
 * @use php vendor/bin/codecept run unit Consumer/PipelineConsumerTest
 */
class PipelineConsumerTest extends \Codeception\TestCase\Test
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
    
    public function testRunPipelineStage()
    {
        $this->createAmqpMocks();
        
        $consumer = new PipelineConsumer(
            $this->amqpConnectionFactoryMock,
            new ChainedExtension([ new ExitOnPostConsumeExtension() ])
        );
    
        $pipelineId = 'test-pipeline';
        $task = new NullTask();
        $task->stageId = 'test';
    
        $pipeline = new TaskPipeline(
            'test-pipeline',
            [ $task ]
        );
    
        $containerMock = Mockery::instanceMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $containerMock->shouldReceive('get')->withArgs([ $pipelineId ])->andReturn($pipeline);
        
        $consumer->setContainer($containerMock);
        
        $consumer->runPipelineStage($pipelineId, 0);
    }
    
}
