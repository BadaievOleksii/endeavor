<?php

namespace Endeavor\Tests\Util;

use Endeavor\Util\JSON;

/**
 * Class JsonTest
 *
 */
class JsonTest extends \Codeception\TestCase\Test
{
    public function testDecodeEnsureExceptionThrownWhenNotString()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        JSON::decode([]);
    }
    
    public function testDecodeEnsureNullReturnedOnEmptyString()
    {
        $result = JSON::decode('');
        $this->assertNull($result);
    }
    
    public function testDecodeEnsureExceptionThrownOnInvalidJson()
    {
        $this->expectException(\InvalidArgumentException::class);
    
        JSON::decode('{{abc:123}');
    }
    
    public function testEncodeEnsureExceptionThrownIfValueIsResource()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        
        $resource = fopen('php://memory', 'r');
        fclose($resource);
        
        JSON::encode($resource);
    }
    
    public function testShouldEncodeArray()
    {
        $this->assertEquals('{"key":"value"}', JSON::encode(['key' => 'value']));
    }
    
    public function testShouldEncodeString()
    {
        $this->assertEquals('"string"', JSON::encode('string'));
    }
    
    public function testShouldEncodeNumeric()
    {
        $this->assertEquals('123.45', JSON::encode(123.45));
    }
    
    public function testShouldEncodeNull()
    {
        $this->assertEquals('null', JSON::encode(null));
    }
}
