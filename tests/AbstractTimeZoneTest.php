<?php

namespace tomchkk\LaravelTimeZones\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;

abstract class AbstractTimeZoneTest extends TestCase
{
    protected const APP_TIMEZONE = 'UTC';

    protected CarbonInterface $default;

    protected function setUp(): void
    {
        parent::setUp();

        // this seems a bit convoluted but we need to be sure that the default
        // instance has the same base format to allow equality comparisons
        $this->default = CarbonImmutable::createFromFormat(
            DB::getQueryGrammar()->getDateFormat(),
            Carbon::now()
        );
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.timezone', $this->getApplicationTimezone($app));
    }

    /**
     * Get application timezone.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return static::APP_TIMEZONE;
    }
}
