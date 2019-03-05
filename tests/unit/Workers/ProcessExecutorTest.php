<?php


namespace Endeavor\Tests\Workers;

use Endeavor\Workers\ProcessExecutor;
use Codeception\Module\UnitHelper;
use Mockery;
use Symfony\Component\Process\Process;


/**
 * Class ProcessExecutorTest
 *
 * @use php vendor/bin/codecept run unit Workers/ProcessExecutorTest
 *
 */
class ProcessExecutorTest extends \Codeception\TestCase\Test
{
    /**
     * @throws \ReflectionException
     */
    public function testExecute()
    {
        $executor = new ProcessExecutor('true');
        
        $processMock = Mockery::instanceMock(Process::class);
        $processMock->shouldReceive('start');
        $processMock->shouldReceive('isRunning')->andReturn(true);
        
        UnitHelper::setPrivateProperty($executor, 'process', $processMock);
        
        $this->assertTrue(
            $executor->execute()
        );
    }
    
    /**
     * @throws \ReflectionException
     */
    public function testWait()
    {
        $executor = new ProcessExecutor('true');
        
        $processMock = Mockery::instanceMock(Process::class);
        $processMock->shouldReceive('wait')->andReturn(true);
        
        UnitHelper::setPrivateProperty($executor, 'process', $processMock);
        
        $this->assertTrue(
            $executor->wait()
        );
    }
}
