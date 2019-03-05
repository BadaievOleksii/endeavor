<?php

namespace Endeavor\Console\Commands;

use Endeavor\Consumer\Extension\ChainedExtension;
use Endeavor\Consumer\Extension\LoggerExtension;
use Endeavor\Consumer\Extension\SleepOnIdleExtension;
use Endeavor\Consumer\Extension\TimeLimitExtension;
use Endeavor\Consumer\PipelineConsumer;
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
 * Class PipelineReceive
 *
 */
class PipelineReceive extends Command implements ContainerAwareInterface, LoggerAwareInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pipeline:receive')
            ->setDescription('Receive and process task from certain pipeline')
            ->addArgument('pipeline-id', InputArgument::REQUIRED, 'Id of needed pipeline')
            ->addArgument('stage-id', InputArgument::REQUIRED, 'Id of the task inside of pipeline')
        ;
    }

    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var AmqpConnectionFactory $amqp */
        $amqp = $this->container->get(AmqpConnectionFactory::class);
    
        /** @var PipelineConsumer $consumer */
        $consumer = new PipelineConsumer(
            $amqp,
            new ChainedExtension([
                new LoggerExtension($this->logger),
                new SleepOnIdleExtension(1000000),
                new TimeLimitExtension(),
            ]),
            null,
            $this->logger
        );
        $consumer->setContainer($this->container);
        $consumer->runPipelineStage(
            $input->getArgument('pipeline-id'),
            $input->getArgument('stage-id')
        );
    }
    
}
