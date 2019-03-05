<?php

namespace Endeavor\Tests\Consumer\Extension;

use Endeavor\Consumer\Extension\LoggerExtension;
use Endeavor\Consumer\Runtime;
use Mockery;
use Psr\Log\LoggerInterface;


/**
 * Class LoggerExtensionTest
 *
 * @use php vendor/bin/codecept run unit Consumer/Extension/LoggerExtensionTest
 *
 */
class LoggerExtensionTest extends \Codeception\TestCase\Test
{
    
    public function testOnStart()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('notice')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onStart($runtime);
    }
    
    
    public function testOnPreConsume()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('debug')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onPreConsume($runtime);
    }
    
    
    public function testOnPreProcess()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('info')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onPreProcess($runtime);
    }
    
    
    public function testOnPostProcess()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('info')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onPostProcess($runtime);
    }
    
    
    public function testOnPostConsume()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('debug')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onPostConsume($runtime);
    }
    
    
    public function testOnIdle()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('debug')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onIdle($runtime);
    }
    
    
    public function testOnInterrupted()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('error')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onInterrupted($runtime);
    }
    
    
    public function testOnFinish()
    {
        $loggerMock = Mockery::instanceMock(LoggerInterface::class);
        $loggerMock->shouldReceive('notice')->once();
        
        $runtime = new Runtime();
        
        $loggerExt = new LoggerExtension($loggerMock);
        $loggerExt->onFinish($runtime);
    }
}
