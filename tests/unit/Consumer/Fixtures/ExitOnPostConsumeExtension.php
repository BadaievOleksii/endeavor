<?php


namespace Endeavor\Tests\Consumer\Fixtures;

use Endeavor\Consumer\Extension\ExtensionInterface;
use Endeavor\Consumer\Extension\NullExtensionTrait;
use Endeavor\Consumer\Runtime;


/**
 * Class ExitOnPostConsumeExtension
 *
 */
class ExitOnPostConsumeExtension implements ExtensionInterface
{
    use NullExtensionTrait;
    
    
    /**
     * {@inheritdoc}
     */
    public function onPostConsume(Runtime $runtime)
    {
        $runtime->isRunning = false;
    }
}
