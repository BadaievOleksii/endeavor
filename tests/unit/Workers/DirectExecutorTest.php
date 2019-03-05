<?php


namespace Endeavor\Tests\Workers;

use Endeavor\Workers\DirectExecutor;


/**
 * Class DirectExecutorTest
 *
 * @use php vendor/bin/codecept run unit Workers/DirectExecutorTest
 *
 */
class DirectExecutorTest extends \Codeception\TestCase\Test
{
    public function testExecute()
    {
        $executor = new DirectExecutor('true');
        
        $this->assertTrue(
            $executor->execute()
        );
    }
    
    public function testWait()
    {
        $executor = new DirectExecutor('true');
        
        $this->assertTrue(
            $executor->wait()
        );
    }
}
