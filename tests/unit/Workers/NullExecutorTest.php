<?php


namespace Endeavor\Tests\Workers;

use Endeavor\Workers\NullExecutor;


/**
 * Class NullExecutorTest
 *
 * @use php vendor/bin/codecept run unit Workers/NullExecutorTest
 *
 */
class NullExecutorTest extends \Codeception\TestCase\Test
{
    public function testExecute()
    {
        $executor = new NullExecutor('');
        
        $this->assertTrue(
            $executor->execute()
        );
    }
    
    public function testWait()
    {
        $executor = new NullExecutor('');
        
        $this->assertTrue(
            $executor->wait()
        );
    }
}
