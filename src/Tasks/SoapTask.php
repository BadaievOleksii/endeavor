<?php

namespace Endeavor\Tasks;

/**
 * Class SoapTask
 *
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/DataEnricher.html
 */
class SoapTask extends PipeTask
{
    /**
     * @var array
     */
    public $data;
    
    /**
     * @var string
     */
    public $wsdl;
    
    /**
     * @var array
     */
    public $options;
    
    /**
     * @var string
     */
    public $method;
    
    
    /**
     * @param array $inputData
     */
    public function input($inputData)
    {
        $this->data = $inputData;
    }
    
}
