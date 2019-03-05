<?php

namespace Endeavor\Tasks;

/**
 * Class DbQueryTask
 *
 * @see http://www.enterpriseintegrationpatterns.com/patterns/messaging/DataEnricher.html
 */
class DbQueryTask extends PipeTask
{
    /**
     * @var string|\stdClass
     */
    public $query;

    /**
     * @var array
     */
    public $connectionConfig;

    /**
     * @param string $inputData
     */
    public function input($inputData)
    {
        $this->query = $inputData;
    }
}