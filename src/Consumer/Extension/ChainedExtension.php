<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Class ChainedExtension
 *
 * Wraps up a stack of extensions, and applies each of them on appropriate calls
 *
 */
class ChainedExtension implements ExtensionInterface
{

    /**
     * @var ExtensionInterface[]
     */
    private $extensions;

    /**
     * @param ExtensionInterface[] $extensions
     */
    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function onStart(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onStart($runtime);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onPreConsume(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onPreConsume($runtime);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onPreProcess(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onPreProcess($runtime);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onPostProcess(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onPostProcess($runtime);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onPostConsume(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onPostConsume($runtime);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onIdle(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onIdle($runtime);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function onInterrupted(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onInterrupted($runtime);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function onFinish(Runtime $runtime)
    {
        foreach ($this->extensions as $extension) {
            $extension->onFinish($runtime);
        }
    }
}
