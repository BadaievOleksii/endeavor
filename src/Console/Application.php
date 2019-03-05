<?php

namespace Endeavor\Console;

use Endeavor\Console\Commands\PipelineReceive;
use Endeavor\Console\Commands\PipelineSend;
use Endeavor\Console\Commands\TaskReceive;
use Endeavor\Console\Commands\TaskSend;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Application
 *
 * Wrapper around Symfony CLI Application for Endeavor built-in commands
 * Provides variable configurations directory
 *
 */
class Application extends BaseApplication
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultCommands()
    {
        return [
            new HelpCommand(),
            new ListCommand(),
            new PipelineSend(),
            new PipelineReceive(),
            new TaskSend(),
            new TaskReceive(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--dir', '-d', InputOption::VALUE_OPTIONAL, 'Set another base working dir', ROOT),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
        ]);
    }
}
