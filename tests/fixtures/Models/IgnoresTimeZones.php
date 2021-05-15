<?php

namespace tomchkk\LaravelTimeZones\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

class IgnoresTimeZones extends Model
{
    protected $dates = [
        'default',
        'alternative',
    ];
}
