<?php

namespace Endeavor\Console\Commands;

use Endeavor\Producer\TaskProducer;
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
 * Class TaskSend
 *
 * @deprecated
 */
class TaskSend extends Command implements ContainerAwareInterface, LoggerAwareInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('task:send')
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
        /** @var \Endeavor\Core\TaskInterface $task */
        $task = $this->container->get(
            $input->getArgument('task')
        );

        $sender = new TaskProducer(
            $this->container->get(AmqpConnectionFactory::class),
            $this->logger
        );

        $sender->send($task);
    }
    
}
