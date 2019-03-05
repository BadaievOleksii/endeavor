<?php

namespace Endeavor\Console\Commands;

use Endeavor\Producer\PipelineProducer;
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
 * Class PipelineSend
 *
 */
class PipelineSend extends Command implements ContainerAwareInterface, LoggerAwareInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var mixed
     */
    protected $inputData;
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pipeline:send')
            ->setDescription('Send the FIRST task from specific pipeline')
            ->addArgument('pipeline-id', InputArgument::REQUIRED, 'Id of needed pipeline')
        ;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Endeavor\Tasks\TaskPipeline $pipeline */
        $pipeline = $this->container->get(
            $input->getArgument('pipeline-id')
        );
        
        /** @var AmqpConnectionFactory $amqp */
        $amqp = $this->container->get(AmqpConnectionFactory::class);
        $sender = new PipelineProducer(
            $amqp,
            $this->logger
        );
        $sender->startPipeline($pipeline, $this->inputData);
    }

    /**
     * Sets input data for pipeline
     *
     * @see PipelineProducer::startPipeline()
     * @param mixed $input
     */
    public function setInputData($input)
    {
        $this->inputData = $input;
    }
    
}
