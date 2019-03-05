<?php

namespace Endeavor\Tests\Consumer\Extension;

use Endeavor\Consumer\Extension\ExitOnIdleExtension;
use Endeavor\Consumer\Runtime;


/**
 * Class ExitOnIdleExtensionTest
 *
 * @use php vendor/bin/codecept run unit Consumer/Extension/ExitOnIdleExtensionTest
 *
 */
class ExitOnIdleExtensionTest extends \Codeception\TestCase\Test
{
    public function testOnIdle()
    {
        $runtime = new Runtime();
        $runtime->isRunning = true;
        
        $ext = new ExitOnIdleExtension();
        $ext->onIdle($runtime);
        $this->assertFalse($runtime->isRunning);
    }
    
}
