<?php

namespace Endeavor\Tests\Util;

use Endeavor\Util\ReflectionUtils;

/**
 * Class ReflectionUtilsTest
 *
 */
class ReflectionUtilsTest extends \Codeception\TestCase\Test
{
    public function testGetClassShortName()
    {
        $fullName = ReflectionUtils::class;
        $shortName = 'ReflectionUtils';
        
        $obj = new $fullName();
        
        $this->assertEquals(
            $shortName,
            ReflectionUtils::getClassShortName($obj)
        );
    }
}
