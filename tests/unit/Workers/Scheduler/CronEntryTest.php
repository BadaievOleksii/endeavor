<?php


namespace Endeavor\Tests\Workers\Scheduler;

use Endeavor\Workers\Scheduler\CronEntry;
use Cron\CronExpression;


/**
 * Class CronEntryTest
 *
 * @use php vendor/bin/codecept run unit Workers/Scheduler/CronEntryTest
 */
class CronEntryTest extends \Codeception\TestCase\Test
{

    public function testValidCronExpression1()
    {
        $valid = '* * * * *';

        $entry = new CronEntry($valid, '');

        $this->assertAttributeInstanceOf(CronExpression::class, 'cron', $entry);

        $expectedCron = CronExpression::factory($valid);
        $expectedCmd = '';

        $this->assertEquals($entry->getCron(), $expectedCron);
        $this->assertEquals($entry->getCmd(), $expectedCmd);
    }

    public function testValidCronExpression2()
    {
        $valid = '*     *       *    *   *';

        $entry = new CronEntry($valid, '');

        $this->assertAttributeInstanceOf(CronExpression::class, 'cron', $entry);
    }

    public function testInvalidCronExpression()
    {
        $invalid = '* ** * *';

        $this->setExpectedException(\InvalidArgumentException::class);

        $entry = new CronEntry($invalid, '');
    }

    public function testToString()
    {
        $valid = '*     *       *    *   *';

        $entry = new CronEntry($valid, 'true');

        $expected = '* * * * * true';

        $this->assertEquals($expected, (string)$entry);
    }

    public function testValidation()
    {
        $this->assertFalse(
            CronEntry::isValid('* * * * *', '')
        );

        $this->assertFalse(
            CronEntry::isValid('*****', 'true')
        );

        $this->assertTrue(
            CronEntry::isValid('* * * * *', 'true')
        );
    }
}
