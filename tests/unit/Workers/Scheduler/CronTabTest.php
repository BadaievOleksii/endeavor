<?php


namespace Endeavor\Tests\Workers\Scheduler;

use Endeavor\Workers\Scheduler\CronEntry;
use Endeavor\Workers\Scheduler\CronTab;


/**
 * Class CronTabTest
 *
 * @use php vendor/bin/codecept run unit Workers/Scheduler/CronTabTest
 */
class CronTabTest extends \Codeception\TestCase\Test
{
    
    public function testFromFile()
    {
        $expected = [
            new CronEntry('1 * * * *', 'true'),
            new CronEntry('* 1 * * *', 'true'),
            new CronEntry('* * 1 * *', 'true'),
            new CronEntry('* * * 1 *', 'true'),
            new CronEntry('* * * * 1', 'true'),
        ];
        
        $crontab = CronTab::fromFile(__DIR__.'/../Fixtures/crontab');
        
        $this->assertEquals(
            5,
            $crontab->count()
        );
        
        $this->assertEquals(
            $expected,
            $crontab->getTab()
        );
        
    }
    
    public function testFromArray()
    {
        $crontab = [
          [ '1 * * * *', 'true', ],
          [ '* 1 * * *', 'true', ],
          [ '* * 1 * *', 'true', ],
          [ '* * * 1 *', 'true', ],
          [ '* * * * 1', 'true', ],
        ];
        
        $crontab = CronTab::fromArray($crontab);
    
        $this->assertEquals(
            5,
            $crontab->count()
        );
    
        $expected = [
            new CronEntry('1 * * * *', 'true'),
            new CronEntry('* 1 * * *', 'true'),
            new CronEntry('* * 1 * *', 'true'),
            new CronEntry('* * * 1 *', 'true'),
            new CronEntry('* * * * 1', 'true'),
        ];
        $this->assertEquals(
            $expected,
            $crontab->getTab()
        );
    
    }
    
    
}
