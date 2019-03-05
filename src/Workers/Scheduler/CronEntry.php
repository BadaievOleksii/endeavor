<?php

namespace Endeavor\Workers\Scheduler;

use Cron\CronExpression;

/**
 * Class CronEntry
 * Represents one entry from crontab, with separated schedule and command parts
 *
 */
class CronEntry
{
    /**
     * Schedule part of crontab entry
     *
     * @var \Cron\CronExpression
     */
    private $cron;
    
    /**
     * Shell command to be executed by this crontab entry
     *
     * @var string
     */
    private $cmd;
    
    /**
     * CronEntry constructor.
     *
     * @param $cronStr
     * @param $cmdStr
     */
    public function __construct($cronStr, $cmdStr)
    {
        $this->cron = CronExpression::factory($cronStr);
        $this->cmd = $cmdStr;
    }
    
    /**
     * Checks schedule part to be fully cron-compatible, and command part to be specified
     *
     * @see \Cron\CronExpression
     *
     * @param string $cronStr schedule part, e.g. `30 8 * * 0`
     * @param string $cmdStr shell command, e.g. `echo hello`
     *
     * @return bool result of validation
     */
    public static function isValid($cronStr, $cmdStr)
    {
        return CronExpression::isValidExpression($cronStr) && !empty($cmdStr);
    }
    
    /**
     * Returns both parts of crontab entry, as it could be in system's crontab
     *
     * @return string
     */
    public function __toString()
    {
        return $this->cron->getExpression() . " " . $this->cmd;
    }
    
    /**
     * @return \Cron\CronExpression wrapped schedule part of this crontab entry
     */
    public function getCron()
    {
        return $this->cron;
    }
    
    /**
     * @return string command of this crontab entry
     */
    public function getCmd()
    {
        return $this->cmd;
    }
}
