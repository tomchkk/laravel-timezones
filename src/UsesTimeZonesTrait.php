<?php

namespace tomchkk\LaravelTimeZones;

/**
 * This trait overrides the default behaviour of Eloquent models to ensure that
 * model datetime attributes are instantiated consistently with the application
 * timezone, or with an alternative timezone if defined on a model's $timezone
 * property.
 *
 * A timezone can still be defined individually on an attribute using accessors
 * and mutators, calling fromDateTime() and asDateTime(), respectively, with a
 * timezone argument.
 */
trait UsesTimeZonesTrait
{
    /**
     * Gets the timezone explicitly defined for a model or, if it's not set,
     * the application timezone, or a default.
     *
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->timezone ?? config('app.timezone', 'UTC');
    }

    /**
     * Overrides HasAttributes::fromDateTime so that any model attribute value
     * being set as a datetime has a timezone set before being persisted.
     *
     * @param mixed $value
     * @param string $timezone
     *
     * @return string|null
     */
    public function fromDateTime($value, $timezone = null): ?string
    {
        // we'll let the parent function parse the datetime as usual, but then
        // set the timezone explicitly
        return empty($value) ? $value : parent::asDateTime($value)
            ->setTimezone($timezone ?? $this->getTimeZone())
            ->format($this->getDateFormat());
    }

    /**
     * Overrides HasAttributes::asDateTime so that any model attribute value
     * already set as a datetime has a timezone when being accessed.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Carbon
     */
    protected function asDateTime($value, $timezone = null)
    {
        // the parent function assumes that a timestamp's timezone will be the
        // application timezone, so we'll shift it to the _actual_ timezone
        return parent::asDateTime($value)->shiftTimeZone(
            $timezone ?? $this->getTimeZone()
        );
    }
}
