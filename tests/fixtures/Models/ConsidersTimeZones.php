<?php

namespace tomchkk\LaravelTimeZones\Tests\Fixtures\Models;

use tomchkk\LaravelTimeZones\Tests\Fixtures\Models\IgnoresTimeZones;
use tomchkk\LaravelTimeZones\UsesTimeZonesTrait;

class ConsidersTimeZones extends IgnoresTimeZones
{
    use UsesTimeZonesTrait;
}
