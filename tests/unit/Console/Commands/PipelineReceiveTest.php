<?php

namespace Endeavor\Tests\Console\Commands;

use Endeavor\Console\Commands\PipelineReceive;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class PipelineReceiveTest
 *
 * @use php vendor/bin/codecept run unit Console/Commands/PipelineReceiveTest
 */
class PipelineReceiveTest extends \Codeception\TestCase\Test
{
    public function testConfigure()
    {
        $command = new PipelineReceive();
        
        $this->assertEquals('pipeline:receive', $command->getName());
        $this->assertEquals('Receive and process task from certan pipeline', $command->getDescription());
        
        $expected = new InputDefinition([
            new InputArgument('pipeline-id', InputArgument::REQUIRED, 'Id of needed pipeline'),
            new InputArgument('stage-id', InputArgument::REQUIRED, 'Id of the task inside of pipeline'),
        ]);
        $this->assertEquals($expected, $command->getDefinition());
    }
}
