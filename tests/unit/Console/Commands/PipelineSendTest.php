<?php

namespace Endeavor\Tests\Console\Commands;

use Endeavor\Console\Commands\PipelineSend;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class PipelineSendTest
 *
 * @use php vendor/bin/codecept run unit Console/Commands/PipelineSendTest
 */
class PipelineSendTest extends \Codeception\TestCase\Test
{
    public function testConfigure()
    {
        $command = new PipelineSend();
        
        $this->assertEquals('pipeline:send', $command->getName());
        $this->assertEquals('Send the FIRST task from specific pipeline', $command->getDescription());
        
        $expected = new InputDefinition([
            new InputArgument('pipeline-id',InputArgument::REQUIRED, 'Id of needed pipeline')
        ]);
        $this->assertEquals($expected, $command->getDefinition());
    }
}
