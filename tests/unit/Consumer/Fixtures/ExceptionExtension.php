<?php


namespace Endeavor\Tests\Consumer\Fixtures;

use Endeavor\Consumer\Extension\ExtensionInterface;
use Endeavor\Consumer\Extension\NullExtensionTrait;
use Endeavor\Consumer\Runtime;


/**
 * Class ExceptionExtension
 *
 */
class ExceptionExtension implements ExtensionInterface
{
    use NullExtensionTrait;
    
    /**
     * {@inheritdoc}
     */
    public function onPreProcess(Runtime $runtime)
    {
        throw new \Exception();
    }
}
