<?php

namespace Endeavor\Tasks;

use Endeavor\Core\TaskProcessorInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Class SoapMockTaskProcessor
 *
 * @deprecated
 *
 */
class SoapMockTaskProcessor implements TaskProcessorInterface
{
    /**
     * @param SoapMockTask $task
     *
     * @return array
     */
    public function process($task)
    {
        $xml = file_get_contents($task->filePath);
        
        $xmlEncoder = new XmlEncoder('Envelope');
        $decoded = $xmlEncoder->decode($xml, null);
        return [ (object) $decoded ];
    }
}