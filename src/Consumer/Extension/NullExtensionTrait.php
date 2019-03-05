<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Trait NullExtensionTrait
 *
 * Stubs methods from ExtensionTrait, so that custom extensions should not implement them all,
 * but only the ones, they are interested in.
 *
 * @see \Endeavor\Consumer\Extension\ExtensionInterface
 */
trait NullExtensionTrait
{
    public function onStart(Runtime $runtime)
    {
    }

    public function onPreConsume(Runtime $runtime)
    {
    }

    public function onPreProcess(Runtime $runtime)
    {
    }

    public function onPostProcess(Runtime $runtime)
    {
    }

    public function onPostConsume(Runtime $runtime)
    {
    }

    public function onIdle(Runtime $runtime)
    {
    }

    public function onInterrupted(Runtime $runtime)
    {
    }

    public function onFinish(Runtime $runtime)
    {
    }
}
