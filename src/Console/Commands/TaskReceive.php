<?php

namespace Endeavor\Console\Commands;

use Endeavor\Consumer\ClassnameResolver;
use Endeavor\Consumer\Extension\ChainedExtension;
use Endeavor\Consumer\Extension\LoggerExtension;
use Endeavor\Consumer\Extension\SleepOnIdleExtension;
use Endeavor\Consumer\Extension\TimeLimitExtension;
use Endeavor\Consumer\TaskConsumer;
use Enqueue\AmqpBunny\AmqpConnectionFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class TestCommand
 *
 * @deprecated
 */
class TaskReceive extends Command implements ContainerAwareInterface, LoggerAwareInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('task:receive')
            ->setDescription('')
            ->addArgument('task', InputArgument::REQUIRED, '')
            ->setHidden(true)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $consumer = new TaskConsumer(
            $this->container->get(AmqpConnectionFactory::class),
            new ChainedExtension([
                new LoggerExtension($this->logger),
                new SleepOnIdleExtension(),
                new TimeLimitExtension(),
            ]),
            new ClassnameResolver()
        );
        $consumer->run(
            $input->getArgument('task')
        );
    }
    
}
