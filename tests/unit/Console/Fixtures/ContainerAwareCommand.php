<?php


namespace Endeavor\Tests\Console\Fixtures;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class ContainerAwareCommand
 *
 */
class ContainerAwareCommand extends Command
{
    use ContainerAwareTrait;

}
