<?php

namespace Endeavor\Workers\Scheduler;

use RegexIterator;
use SplFileObject;

/**
 * Class Crontab
 * Represents crontab as CronEntry[]
 * Can be created both from classic crontab file, and from array with cron/cmd values
 *
 */
class CronTab implements \Countable
{
    
    /**
     * Entries in this crontab
     *
     * @var CronEntry[]
     */
    protected $tab;
    
    /**
     * CronTab constructor.
     *
     * @see CronTab::generateTab() for $lines
     *
     * @param array $lines lines of crontab
     * @param bool $strict on true - will throw an exception on invalid cron entry, on false - silently skip it
     */
    public function __construct(array $lines = [], $strict = false)
    {
        $this->tab = $this->generateTab($lines, $strict);
    }
    
    /**
     * Generates CronEntry[] from array, where each element can be:
     *  - array with two strings, first is schedule and second is command.
     *   Example:
     *   [
     *     '* * * * *',
     *     'echo foo',
     *   ]
     *  - string with one line from crontab
     *   Example:
     *   [
     *     '* * * * * echo foo'
     *   ]
     *
     * Creates new CronEntry only for lines, that passed CronEntry::isValid()
     *
     * @param array $lines lines of crontab
     * @param bool $strict on true - will throw an exception on invalid cron entry, on false - silently skip it
     *
     * @return CronEntry[] resulting array of CronEntry, can be empty if no lines passed checking
     */
    protected function generateTab(array $lines, $strict)
    {
        $crontab = [];
        
        foreach ($lines as $line) {
    
            if (!CronEntry::isValid($line[0], $line[1])) {
                if ($strict) {
                    throw new \InvalidArgumentException("Cannot create crontab, invalid line found: `{$line[0]} {$line[1]}`");
                }
        
                continue;
            }
    
            $crontab[] = new CronEntry($line[0], $line[1]);
        }
        
        return $crontab;
    }
    
    /**
     * Factory method for creating CronTab from file
     * Parses classic crontab file, and returns new instance of CronTab, with all correct lines in it
     * Regexp also filters out lines, strating with `#` (bash comment)
     *
     * @param string $fileName full path to file
     *
     * @return CronTab new instance, filled with entries
     */
    public static function fromFile($fileName)
    {
        $file = new SplFileObject($fileName);
        
        $regexp = new RegexIterator($file, '/^((?:[^\#\s]+\s+){5})(\S.*)$/m', \RegexIterator::GET_MATCH);
        
        $array = array_reduce(
            iterator_to_array($regexp),
            function ($carry, $one) {
                $cron = preg_replace('/\s+/', ' ', trim($one[1]));
                $cmd = $one[2];
                
                $carry[] = [ $cron, $cmd ];
                
                return $carry;
            },
            []
        );
        
        $file = null;
        
        return new self($array);
    }
    
    /**
     * Factory method for creating CronTab from array
     *
     * @param array $array
     *
     * @return CronTab new instance
     */
    public static function fromArray($array)
    {
        return new self($array);
    }
    
    /**
     * Returns entries in this crontab
     *
     * @return CronEntry[] tasks
     */
    public function getTab()
    {
        return $this->tab;
    }
    
    /**
     * Returns count of entries in crontab
     *
     * @return int count of entries
     */
    public function count()
    {
        return count($this->tab);
    }
}
