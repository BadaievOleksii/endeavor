<?php

namespace Endeavor\Workers\Scheduler;

use Endeavor\Workers\ExecutorInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Class CronRunnerLoggerAwareTrait
 * Checks all entries from CronTab, and runs due ones using given ExecutorInterface. Logs actions with logger in LoggerAwareTrait
 * Default logger is NullLogger, can be changed with setLogger()
 *
 */
class CronRunner
{
    use LoggerAwareTrait;
    
    /**
     * CronTab to run
     *
     * @var CronTab
     */
    protected $crontab;
    
    /**
     * Class, implementing ExecutorInterface to use for running commands in crontab
     *
     * @var string
     */
    protected $executorClass;
    
    /**
     * CronRunner constructor.
     *
     * @param CronTab $crontab crontab to use
     * @param string $executorClass executor class to use; if not given - defaults to NullExecutor
     */
    public function __construct(CronTab $crontab, $executorClass)
    {
        $this->crontab = $crontab;
        $this->executorClass = $executorClass;
        $this->logger = new NullLogger();
    }
    
    /**
     * Runs all entries in $crontab using $executor
     * Checks schedule for each, and if due, executes it
     *
     * @return int number of entries, executed successfully
     */
    public function runAll()
    {
        $this->logger->info('Started CronRunner', [ 'to_execute' => $this->crontab->count() ]);
        
        /** @var CronEntry[] $onSchedule */
        $onSchedule = array_filter($this->crontab->getTab(), function ($cronEntry) {
            return $cronEntry->getCron()->isDue();
        });
        
        /** @var ExecutorInterface[] $executed */
        $executed = [];
        foreach ($onSchedule as $cronEntry) {
            $this->logger->notice('CronEntry on schedule now, executing', [ 'entry' => (string) $cronEntry ]);
            try {
                /** @var ExecutorInterface $executor */
                $executor = new $this->executorClass($cronEntry->getCmd());
                if (!$executor instanceof ExecutorInterface) {
                    throw new \InvalidArgumentException('Given executor class doesn\'t implement ExecutorInterface');
                }
                $executor->execute();
                $executed[] = $executor;
            } catch (\Exception $exception) {
                $this->logger->error('Exception caught when executing CronEntry', [ 'exception' => $exception ]);
            }
        }
        
        $this->logger->info('Crontab executed, waiting for all processes to finish...');
        foreach ($executed as $executor) {
            $executor->wait();
        }
        $this->logger->info('Finished CronRunner', [ 'executed' => count($executed) ]);
        
        return count($executed);
    }
}
