<?php

namespace Endeavor\Tests\Console\Commands;

use Endeavor\Console\Commands\TaskSend;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class TaskSendTest
 *
 * @use php vendor/bin/codecept run unit Console/Commands/TaskSendTest
 */
class TaskSendTest extends \Codeception\TestCase\Test
{
    public function testConfigure()
    {
        $command = new TaskSend();
        
        $this->assertEquals('task:send', $command->getName());
        $this->assertEquals('', $command->getDescription());
        
        $expected = new InputDefinition([
            new InputArgument('task', InputArgument::REQUIRED, ''),
        ]);
        $this->assertEquals($expected, $command->getDefinition());
        
        $this->assertTrue($command->isHidden());
    }
}
