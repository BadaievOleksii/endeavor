<?php

namespace Endeavor\Tests\Consumer;

use Endeavor\Consumer\ClassnameResolver;

/**
 * Class ClassnameResolverTest
 *
 * @use php vendor/bin/codecept run unit Consumer/ClassnameResolverTest
 */
class ClassnameResolverTest extends \Codeception\TestCase\Test
{
    public function testResolveProcessor()
    {
        $task = new \Endeavor\Tasks\SoapTask();
        
        $resolver = new ClassnameResolver();
    
        $expected = \Endeavor\Tasks\SoapTask::class;
        $result = $resolver->resolveProcessor($task);
        
        $this->assertEquals($expected, $result);
    }
}
