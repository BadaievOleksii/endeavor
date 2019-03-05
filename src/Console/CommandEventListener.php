<?php

namespace Endeavor\Console;

use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CommandEventListener
 *
 * Loads Endeavor-specific container
 * Intended for Endeavor's built-in commands only
 *
 */
class CommandEventListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $containerConfigName = 'services.yml';

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'loadEndeavorContainer',
        ];

    }

    /**
     * Loads Endeavor specific container from services.yml in the --dir folder
     *
     * @param ConsoleCommandEvent $commandEvent
     * @throws \Exception
     */
    public function loadEndeavorContainer(ConsoleCommandEvent $commandEvent)
    {
        $workingDirectory = $commandEvent->getInput()->getOption('dir');

        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader(
            $containerBuilder,
            new FileLocator([
                $workingDirectory,
                $workingDirectory . '/resources',
                $workingDirectory . '/config',
            ])
        );
        $loader->load($this->containerConfigName);

        $containerBuilder->setParameter('endeavor.rootdir', $workingDirectory);
        $containerBuilder->compile();

        if (method_exists($commandEvent->getCommand(), 'setContainer')){
            $commandEvent->getCommand()->setContainer($containerBuilder);
        }

        $containerBuilder->get('monolog.logger')->pushHandler(
            new ConsoleHandler($commandEvent->getOutput())
        );
    }

}
