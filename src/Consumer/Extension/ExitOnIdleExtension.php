<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Class ExitOnIdleExtension
 *
 */
class ExitOnIdleExtension implements ExtensionInterface
{
    use NullExtensionTrait;

    /**
     * {@inheritdoc}
     */
    public function onIdle(Runtime $runtime)
    {
        $runtime->isRunning = false;
    }

}
