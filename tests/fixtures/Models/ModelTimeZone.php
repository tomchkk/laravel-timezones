<?php

namespace tomchkk\LaravelTimeZones\Tests\Fixtures\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use tomchkk\LaravelTimeZones\UsesTimeZonesTrait;

class ModelTimeZone extends Model
{
    use UsesTimeZonesTrait;

    protected $timezone = 'Asia/Kuala_lumpur';
    protected $dates = [
        'utc',
        'default',
        'europe_london',
        'europe_paris',
    ];

    public function setUtcAttribute($value): void
    {
        $this->attributes['utc'] = $this->fromDateTime($value, 'UTC');
    }

    public function getUtcAttribute($value): DateTimeInterface
    {
        return $this->asDateTime($value, 'UTC');
    }

    public function setEuropeLondonAttribute($value): void
    {
        $this->attributes['europe_london'] = $this->fromDateTime($value, 'Europe/London');
    }

    public function getEuropeLondonAttribute($value): DateTimeInterface
    {
        return $this->asDateTime($value, 'Europe/London');
    }

    public function setEuropeParisAttribute($value): void
    {
        $this->attributes['europe_paris'] = $this->fromDateTime($value, 'Europe/Paris');
    }

    public function getEuropeParisAttribute($value): DateTimeInterface
    {
        return $this->asDateTime($value, 'Europe/Paris');
    }
}
