<?php

namespace Endeavor\Tests\Console;


use Endeavor\Console\CommandEventListener;
use Endeavor\Tests\Console\Fixtures\ContainerAwareCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandEventListenerTest extends \Codeception\Test\Unit
{

    public function testGetSubscribedEvents()
    {
        $this->assertArrayHasKey(ConsoleEvents::COMMAND, CommandEventListener::getSubscribedEvents());
    }

    public function testLoadEndeavorContainer()
    {
        $eventSubscriber = new CommandEventListener();

        $command = new ContainerAwareCommand();

        $commandEvent = new ConsoleCommandEvent(
            $command,
            new ArrayInput(['--dir' => __DIR__ . '/Fixtures/'], new InputDefinition([new InputOption('--dir', '-d', InputOption::VALUE_OPTIONAL, 'Set another base working dir', ROOT)])),
            new NullOutput()
        );

        $eventSubscriber->loadEndeavorContainer($commandEvent);

        $this->assertAttributeInstanceOf(ContainerBuilder::class, 'container', $command);
    }
}
