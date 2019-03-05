<?php

namespace Endeavor\Tasks;

/**
 * Class SoapMockTask
 *
 * @deprecated
 *
 */
class SoapMockTask extends PipeTask
{
    /**
     * @var array
     */
    public $data;
    
    /**
     * @var string
     */
    public $filePath;

    /**
     * @param array $inputData
     */
    public function input($inputData)
    {
        $this->data = $inputData;
    }
}