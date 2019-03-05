<?php

namespace Endeavor\Tests\Consumer;

use Endeavor\Consumer\Runtime;
use Endeavor\Tests\Tasks\Fixtures\NullTask;
use Exception;

class RuntimeTest extends \Codeception\Test\Unit
{
    public function testJsonSerializable()
    {
        $runtime = new Runtime();
        $result = $runtime->jsonSerialize();

        $this->assertArrayHasKey('task', $result);
        $this->assertArrayHasKey('running', $result);
        $this->assertArrayNotHasKey('exception', $result);

        $runtime->task = new NullTask();
        $runtime->taskResult = 'test_result';
        $runtime->exception = new Exception('test_message');

        $result = $runtime->jsonSerialize();

        $this->assertArrayHasKey('exception', $result);
        $this->assertEquals(NullTask::class, $result['task']['class']);
        $this->assertJson($result['task']['data']);
        $this->assertJson($result['task']['result']);

        $this->assertEquals(Exception::class, $result['exception']['class']);
        $this->assertEquals('test_message', $result['exception']['message']);
        $this->assertTrue(is_string($result['exception']['trace']));

    }
}
