<?php

namespace tomchkk\LaravelTimeZones\Tests;

use Carbon\Carbon;
use tomchkk\LaravelTimeZones\Tests\AbstractTimeZoneTest;
use tomchkk\LaravelTimeZones\Tests\Fixtures\Models\AttributeTimeZone;
use tomchkk\LaravelTimeZones\Tests\Fixtures\Models\ModelTimeZone;

class ModelTimeZoneTest extends AbstractTimeZoneTest
{
    protected const APP_TIMEZONE = 'UTC';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/fixtures/migrations');
    }

    /**
     * Tests that a model considers timezones where it has a datetime attribute
     * using the application timezone and another using an alternative timezone.
     *
     * @return void
     */
    public function testModelWithAttributeTimeZone()
    {
        $model = new AttributeTimeZone();

        $model->default = $this->default;
        $model->europe_paris = $this->default->setTimezone('Europe/Paris');

        $this->assertEquals(self::APP_TIMEZONE, $model->default->tzName);
        $this->assertEquals('Europe/Paris', $model->europe_paris->tzName);

        $this->assertTrue($model->default->equalTo($model->europe_paris));

        $model->save();
        $model->refresh();

        // let's check everything is still as expected after saving/re-fetching

        $this->assertEquals(self::APP_TIMEZONE, $model->default->tzName);
        $this->assertEquals('Europe/Paris', $model->europe_paris->tzName);

        $this->assertTrue($model->default->equalTo($this->default));
        $this->assertTrue($model->europe_paris->equalTo($this->default));

        $this->assertEquals(self::APP_TIMEZONE, $model->created_at->tzName);
        $this->assertEquals(self::APP_TIMEZONE, $model->updated_at->tzName);
    }

    /**
     * Tests that a model considers a specific timezone set on a model, as well
     * as alternative timezones specified for attributes.
     *
     * @return void
     */
    public function testModelWithTimeZone()
    {
        $model = new ModelTimeZone();

        $model->default = $this->default;
        $model->utc = $this->default->setTimezone('UTC');
        $model->europe_london = $this->default->setTimezone('Europe/London');
        $model->europe_paris = $this->default->setTimezone('Europe/Paris');

        $this->assertEquals($model->getTimeZone(), $model->default->tzName);
        $this->assertEquals('UTC', $model->utc->tzName);
        $this->assertEquals('Europe/London', $model->europe_london->tzName);
        $this->assertEquals('Europe/Paris', $model->europe_paris->tzName);

        $this->assertTrue($model->default->equalTo($model->utc));
        $this->assertTrue($model->utc->equalTo($model->europe_london));
        $this->assertTrue($model->europe_london->equalTo($model->europe_paris));

        $model->save();
        $model->refresh();

        // let's check everything is still as expected after saving/re-fetching

        $this->assertEquals($model->getTimeZone(), $model->default->tzName);
        $this->assertEquals('UTC', $model->utc->tzName);
        $this->assertEquals('Europe/London', $model->europe_london->tzName);
        $this->assertEquals('Europe/Paris', $model->europe_paris->tzName);

        $this->assertTrue($model->default->equalTo($this->default));
        $this->assertTrue($model->utc->equalTo($this->default));
        $this->assertTrue($model->europe_london->equalTo($this->default));
        $this->assertTrue($model->europe_paris->equalTo($this->default));

        // model timestamps will get the model's timezone
        $this->assertEquals($model->getTimeZone(), $model->created_at->tzName);
        $this->assertEquals($model->getTimeZone(), $model->updated_at->tzName);
    }

    public function testQueriesOnModelWithTimeZone()
    {
        $model = new ModelTimeZone();

        $model->default = $this->default;
        $model->utc = $this->default->setTimezone('UTC');
        $model->europe_london = $this->default->setTimezone('Europe/London');
        $model->europe_paris = $this->default->setTimezone('Europe/Paris');

        $model->save();

        // Unless the timezone is set on the Carbon::now() call, it's bound
        // value will default to the application timezone, meaning no results:
        $query = ModelTimeZone::query()->where('default', '<=', Carbon::now());
        $this->assertSame(0, $query->count());
        $query = ModelTimeZone::query()->where('europe_london', '<=', Carbon::now());
        $this->assertSame(0, $query->count());
        $query = ModelTimeZone::query()->where('europe_paris', '<=', Carbon::now());
        $this->assertSame(0, $query->count());

        // Now we'll get results because we're using known timezones
        $query = ModelTimeZone::query()->where('utc', '<=', Carbon::now(/** using app timezone */));
        $this->assertSame(1, $query->count());
        $query = ModelTimeZone::query()->where('default', '<=', Carbon::now($model->getTimeZone()));
        $this->assertSame(1, $query->count());
        $query = ModelTimeZone::query()->where('europe_london', '<=', Carbon::now('Europe/London'));
        $this->assertSame(1, $query->count());
        $query = ModelTimeZone::query()->where('europe_paris', '<=', Carbon::now('Europe/Paris'));
        $this->assertSame(1, $query->count());
    }
}
