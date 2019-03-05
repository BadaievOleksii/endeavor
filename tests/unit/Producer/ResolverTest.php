<?php

namespace Endeavor\Tests\Producer;

use Endeavor\Producer\Resolver;
use Endeavor\Tests\Producer\Fixtures\TestTask;

/**
 * Class ResolverTest
 *
 * @use vendor/bin/codecept run unit --debug Producer/ResolverTest
 */
class ResolverTest extends \Codeception\TestCase\Test
{
    /**
     * @return void
     */
    public function testResolveQueueName()
    {
        $resolver = new Resolver();

        $this->assertEquals(TestTask::class, $resolver->resolveQueueName(new TestTask()));
    }
}
