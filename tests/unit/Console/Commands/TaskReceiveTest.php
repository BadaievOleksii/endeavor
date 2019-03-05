<?php

namespace Endeavor\Tests\Console\Commands;

use Endeavor\Console\Commands\TaskReceive;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class TaskReceiveTest
 *
 * @use php vendor/bin/codecept run unit Console/Commands/TaskReceiveTest
 */
class TaskReceiveTest extends \Codeception\TestCase\Test
{
    public function testConfigure()
    {
        $command = new TaskReceive();
        
        $this->assertEquals('task:receive', $command->getName());
        $this->assertEquals('', $command->getDescription());
        
        $expected = new InputDefinition([
            new InputArgument('task', InputArgument::REQUIRED, ''),
        ]);
        $this->assertEquals($expected, $command->getDefinition());
        
        $this->assertTrue($command->isHidden());
    }
}
