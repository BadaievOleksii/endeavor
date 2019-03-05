<?php

namespace Endeavor\Tests\Console;

use Endeavor\Console\Application;
use Endeavor\Console\Commands\PipelineReceive;
use Endeavor\Console\Commands\PipelineSend;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;


/**
 * Class ApplicationTest
 *
 * @use php vendor/bin/codecept run unit Console/ApplicationTest
 */
class ApplicationTest extends \Codeception\TestCase\Test
{
    public function testDefault()
    {
        $expected = new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--dir', '-d', InputOption::VALUE_OPTIONAL, 'Set another base working dir', ROOT),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
        ]);
        
        $baseApp = new Application();
        
        $this->assertEquals($expected, $baseApp->getDefaultInputDefinition());

        $commands = $baseApp->getDefaultCommands();

        $this->assertContains(new HelpCommand(), $commands, '', false, false);
        $this->assertContains(new ListCommand(), $commands, '', false, false);
        $this->assertContains(new PipelineReceive(), $commands, '', false, false);
        $this->assertContains(new PipelineSend(), $commands, '', false, false);
    }


}
