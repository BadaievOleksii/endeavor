<?php

namespace Endeavor\Tests\Tasks;

use Endeavor\Tasks\SoapMockTask;
use Endeavor\Tasks\SoapMockTaskProcessor;


/**
 * Class SoapMockTaskProcessorTest
 *
 * @use php vendor/bin/codecept run unit Tasks/SoapMockTaskProcessorTest
 *
 */
class SoapMockTaskProcessorTest extends \Codeception\TestCase\Test
{
    public function testConvert()
    {
        $expected = new \stdClass();
        $expected->Body = [
            'GetPacksForGoodsProductResponse' => [
                'GetPacksForGoodsProductResult' => [
                    'CountryCode' => 'US',
                    'HwProductNumber' => '643086-B21',
                    'Locale' => 'english',
                ],
            ],
        ];
        $expected = [ $expected ];
        
        $someData = new \stdClass();
        $someData->testData = 'testData';
        $task = new SoapMockTask();
        $task->filePath = __DIR__.'/Fixtures/soap-mock-test.xml';
        $task->input($someData);
        
        $processor = new SoapMockTaskProcessor();
        $result = $processor->process($task);
        
        $this->assertEquals($expected, $result);
    }
}
