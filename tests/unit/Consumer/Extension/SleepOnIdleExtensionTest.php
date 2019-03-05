<?php

namespace Endeavor\Tests\Consumer\Extension;

use Endeavor\Consumer\Extension\SleepOnIdleExtension;
use Endeavor\Consumer\Runtime;

/**
 * Class SleepOnIdleExtensionTest
 *
 * @use php vendor/bin/codecept run unit Consumer/Extension/SleepOnIdleExtensionTest
 *
 */
class SleepOnIdleExtensionTest extends \Codeception\TestCase\Test
{
    public function testOnIdle()
    {
        $runtime = new Runtime();
        
        $ext = new SleepOnIdleExtension(1);
        $ext->onIdle($runtime);
    }
    
}
