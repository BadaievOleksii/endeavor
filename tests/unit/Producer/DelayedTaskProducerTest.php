<?php
namespace Producer;


use Endeavor\Core\DelayedTaskInterface;
use Endeavor\Producer\DelayedTaskProducer;
use Endeavor\Producer\Resolver;
use Endeavor\Tests\Producer\Fixtures\TestTask;
use Endeavor\Tests\Tasks\Fixtures\NullTask;
use Codeception\Module\UnitHelper;
use Enqueue\AmqpBunny\AmqpConnectionFactory;
use Mockery;

class DelayedTaskProducerTest extends \Codeception\Test\Unit
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
        $task = new TestTask();

        $this->createAmqpMocks();
        $this->amqpQueueMock->shouldReceive('setArguments')->withArgs([
            [
                'x-dead-letter-exchange' => '',
                'x-message-ttl' => -1,
                'x-dead-letter-routing-key' => get_class($task),
            ]
        ]);

        $producer = new DelayedTaskProducer($this->amqpConnectionFactoryMock, null, new Resolver());
        $producer->send($task);
    }

    // tests
    public function testGetDelayedQueueArguments()
    {
        $taskMock = Mockery::instanceMock(DelayedTaskInterface::class);
        $taskMock->shouldReceive('getTTL')->andReturn(-1);

        $amqpContextMock = Mockery::instanceMock(\Interop\Amqp\AmqpContext::class);
        $amqpContextMock->shouldReceive('close')->once();

        /** @var AmqpConnectionFactory|Mockery\MockInterface $amqpConnectionFactoryMock */
        $amqpConnectionFactoryMock = Mockery::instanceMock(\Enqueue\AmqpBunny\AmqpConnectionFactory::class);
        $amqpConnectionFactoryMock->shouldReceive('createContext')->andReturn($amqpContextMock);

        $producer = new DelayedTaskProducer($amqpConnectionFactoryMock, null, new Resolver());

        $result = UnitHelper::invokeMethod($producer, 'getDelayedQueueArguments', [$taskMock]);

        $this->assertEquals(
            $result,
            [
                'x-dead-letter-exchange' => '',
                'x-message-ttl' => -1,
                'x-dead-letter-routing-key' => get_class($taskMock),
            ]
        );
    }

    public function testGetDestinationRouteHasSuffix()
    {
        $task = new TestTask();
        $producer = new DelayedTaskProducer(new AmqpConnectionFactory(), null, new Resolver());

        $result = UnitHelper::invokeMethod($producer, 'getDestinationRoute', [$task]);
        $this->assertStringEndsWith('.delayed', $result);
    }

    public function testGetDestinationRouteThrowsExceptionOnInvalidTask()
    {
        $task = new NullTask();
        $producer = new DelayedTaskProducer(new AmqpConnectionFactory(), null, new Resolver());

        $this->expectException(\InvalidArgumentException::class);
        $result = UnitHelper::invokeMethod($producer, 'getDestinationRoute', [$task]);
    }

}
