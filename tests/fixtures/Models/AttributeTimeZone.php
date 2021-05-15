<?php

namespace tomchkk\LaravelTimeZones\Tests\Fixtures\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use tomchkk\LaravelTimeZones\UsesTimeZonesTrait;

class AttributeTimeZone extends Model
{
    use UsesTimeZonesTrait;

    protected $dates = [
        'default',
        'europe_paris'
    ];

    public function setEuropeParisAttribute($value): void
    {
        $this->attributes['europe_paris'] = $this->fromDateTime($value, 'Europe/Paris');
    }

    public function getEuropeParisAttribute($value): DateTimeInterface
    {
        return $this->asDateTime($value, 'Europe/Paris');
    }
}
