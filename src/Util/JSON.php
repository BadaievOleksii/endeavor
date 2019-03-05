<?php


namespace Endeavor\Util;

/**
 * Class JSON
 *
 * Wrapper for json_encode, json_decode with exceptions
 */
class JSON
{
    /**
     * Decodes JSON string into array (or object)
     *
     * @param string $string JSON-string
     * @param bool $associative
     *
     * @return array|object
     */
    public static function decode($string, $associative = true)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException('JSON::decode() accepts only non-empty string argument');
        }

        if (empty($string)) {
            return null;
        }

        $decoded = json_decode($string, $associative);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf(
                'The malformed json given. Error %s and message %s',
                json_last_error(),
                json_last_error_msg()
            ));
        }

        return $decoded;
    }


    /**
     * Encodes object/array into json string
     *
     * @param mixed $value
     * @param int $options flags for json_encode()
     *
     * @return string
     */
    public static function encode($value, $options = JSON_UNESCAPED_UNICODE)
    {
        $encoded = json_encode($value, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(sprintf(
                'Could not encode value into json. Error %s and message %s',
                json_last_error(),
                json_last_error_msg()
            ));
        }

        return $encoded;
    }
}