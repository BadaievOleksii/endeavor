<?php

namespace Endeavor\Util;

/**
 * Class ReflectionUtils
 *
 */
class ReflectionUtils
{
    /**
     * Returns class name without namespace
     *
     * @param object $object
     * @return string
     */
    public static function getClassShortName($object)
    {
        $path = explode('\\', get_class($object));
        return array_pop($path);
    }
}
