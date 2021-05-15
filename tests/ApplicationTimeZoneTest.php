<?php

namespace tomchkk\LaravelTimeZones\Tests;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use tomchkk\LaravelTimeZones\Tests\AbstractTimeZoneTest;
use tomchkk\LaravelTimeZones\Tests\Fixtures\Models\ConsidersTimeZones;
use tomchkk\LaravelTimeZones\Tests\Fixtures\Models\IgnoresTimeZones;

class ApplicationTimeZoneTest extends AbstractTimeZoneTest
{
    protected const APP_TIMEZONE = 'Asia/Kuala_lumpur';

    private CarbonInterface $europeLondon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->europeLondon = $this->default->setTimezone('Europe/London');
    }

    /**
     * Tests the differences of how an Eloquent model handles time zones with
     * and without the UsesTimeZonesTrait.
     *
     * @dataProvider modelWithApplicationTimeZoneProvider
     *
     * @param Model $model
     * @param bool $considersTimeZones
     *
     * @return void
     */
    public function testModelWithApplicationTimeZone(Model $model, bool $considersTimeZones): void
    {
        $model->default = $this->default;

        // we can see here that the model's 'default' datetime attribute has
        // been set with the application timezone:
        $this->assertEquals(self::APP_TIMEZONE, $model->default->tzName);

        // and we can also see that these two datetimes are equivalent, despite
        // having different timezones:
        $this->assertTrue($this->europeLondon->equalTo($model->default));
        $this->assertNotEquals($this->europeLondon->tzName, $model->default->tzName);

        // however, the problems start when we assign a datetime to a model
        // attribute where the timezone is not the application timezone:
        $model->alternative = $this->europeLondon;

        // we expect that the 'alternative' timezone has been set to the
        // application timezone:
        $this->assertEquals(self::APP_TIMEZONE, $model->alternative->tzName);

        if ($considersTimeZones === false) {
            // but we don't expect that '$alternative' is now _not_ equal to
            // the same instance that was just assigned to it:
            $this->assertFalse($model->alternative->equalTo($this->europeLondon));
            // their UTC values are different!
            $this->assertNotSame(
                $this->europeLondon->toIso8601ZuluString(),
                $model->alternative->toIso8601ZuluString()
            );
        } else {
            // ok - so now we know that these two datetimes are equivalent
            $this->assertTrue($model->alternative->equalTo($this->europeLondon));
            // as are they're UTC values
            $this->assertSame(
                $this->europeLondon->toIso8601ZuluString(),
                $model->alternative->toIso8601ZuluString()
            );
        }
    }

    /**
     * modelWithApplicationTimeZoneProvider
     *
     * @return array
     */
    public function modelWithApplicationTimeZoneProvider(): array
    {
        return [
            [new IgnoresTimeZones(), false],
            [new ConsidersTimeZones(), true],
        ];
    }
}
