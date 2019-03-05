<?php

namespace Endeavor\Tests\Consumer\Extension;

use Endeavor\Consumer\Extension\ChainedExtension;
use Endeavor\Consumer\Extension\ExtensionInterface;
use Endeavor\Consumer\Runtime;
use Mockery;
use Psr\Log\LoggerInterface;


/**
 * Class ChainedExtensionTest
 *
 * @use php vendor/bin/codecept run unit Consumer/Extension/ChainedExtensionTest
 *
 */
class ChainedExtensionTest extends \Codeception\TestCase\Test
{
    
    public function testOnStart()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onStart')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onStart($runtime);
    }
    
    
    public function testOnPreConsume()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onPreConsume')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onPreConsume($runtime);
    }
    
    
    public function testOnPreProcess()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onPreProcess')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onPreProcess($runtime);
    }
    
    
    public function testOnPostProcess()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onPostProcess')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onPostProcess($runtime);
    }
    
    
    public function testOnPostConsume()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onPostConsume')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onPostConsume($runtime);
    }
    
    
    public function testOnIdle()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onIdle')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onIdle($runtime);
    }
    
    
    public function testOnInterrupted()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onInterrupted')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onInterrupted($runtime);
    }
    
    
    public function testOnFinish()
    {
        $extensionMock = Mockery::instanceMock(ExtensionInterface::class);
        $extensionMock->shouldReceive('onFinish')->once();
    
        $runtime = new Runtime();
    
        $loggerExt = new ChainedExtension([ $extensionMock ]);
        $loggerExt->onFinish($runtime);
    }
}
