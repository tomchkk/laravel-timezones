laravel-timezones
=================

Add consistency to Laravel's handling of timezones
--------------------------------------------------

Out of the box, unless using an attribute mutator method, Laravel will set and persist a datetime attribute as-is - i.e. without first setting the timezone to the application (or any other) timezone. However, on fetching the value from the database, it also doesn't explicitly set any timezone on an instantiated datetime. The result of this is that a datetime persisted in a specific timezone, when fetched, can have the correct string value, but a completely different timezone.

This has been raised as a framework bug a number of times - [e.g.](https://github.com/laravel/framework/issues/19865) - but the thinking behind it is that it should be the user who decides in which timezone datetimes should be saved (despite the fact that the application assumes to know in which timezone to instantiate a datetime from the database!).

_laravel-timezones_ provides a trait, `UsesTimeZonesTrait`, which overrides the default behaviour of the `fromDateTime` and `asDateTime` methods (of the `HasAttributes` trait) to ensure consistent and flexible handling of datetimes with timezones.

## Quickstart

### Application timezone

Add `UsesTimeZonesTrait` to models individually, or to a base model, and your models' datetime attributes will consistently be instantiated using the application timezone.

### Model timezone

Add a `$timezone` property - e.g. `protected $timezone = 'Europe/London'` - to your model to define an alternative timezone for that model alone. __N.B.__ This timezone will also apply to the model's `created_at` and `updated_at` timestamp attributes.

### Attribute timezone

Add mutator and accessor attribute methods to your model and use the methods from `UsesTimeZonesTrait` to define alternative timezones on individual attributes, overriding any timezone also specified in the model's `$timezone` property or by the application.

```php

public function setEuropeLondonAttribute($value): void
{
    $this->attributes['europe_london'] = $this->fromDateTime($value, 'Europe/London');
}

public function getEuropeLondonAttribute($value): DateTimeInterface
{
    return $this->asDateTime($value, 'Europe/London');
}

```
