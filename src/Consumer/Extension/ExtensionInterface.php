<?php

namespace Endeavor\Consumer\Extension;

use Endeavor\Consumer\Runtime;

/**
 * Interface ExtensionInterface
 * Provides methods, executed at certain steps of the Consumer workflow
 * This allows to extend consumer with custom logging, some task modifications, sending replies, etc
 *
 */
interface ExtensionInterface
{

    /**
     * Executed only once at the very beginning of the consumption.
     *
     * @param Runtime $runtime
     */
    public function onStart(Runtime $runtime);

    /**
     * Executed at every new cycle before we asked a broker for a new message.
     * The consumption could be interrupted at this step.
     *
     * @param Runtime $runtime
     */
    public function onPreConsume(Runtime $runtime);

    /**
     * Executed when a new message is received from a broker but before it was passed to processor
     * The consumption could be interrupted at this step but it exits after the message is processed.
     *
     * @param Runtime $runtime
     */
    public function onPreProcess(Runtime $runtime);

    /**
     * Executed when a message is processed by a processor or a result was set in onPreReceived method.
     * BUT before the message status was sent to the broker
     * The consumption could be interrupted at this step but it exits after the message is processed.
     *
     * @param Runtime $runtime
     */
    public function onPostProcess(Runtime $runtime);

    /**
     * Executed when a message is processed by a processor.
     * The consumption could be interrupted at this step but it exits after the message is processed.
     *
     * @param Runtime $runtime
     */
    public function onPostConsume(Runtime $runtime);

    /**
     * Called each time at the end of the cycle if nothing was done.
     *
     * @param Runtime $runtime
     */
    public function onIdle(Runtime $runtime);

    /**
     * Called when the consumption was interrupted by an exception
     *
     * @param Runtime $runtime
     */
    public function onInterrupted(Runtime $runtime);

    /**
     * Called once when the consumer finished any consuming and gracefully shutdowns (e.g. regular restarts)
     *
     * @param Runtime $runtime
     */
    public function onFinish(Runtime $runtime);
}
