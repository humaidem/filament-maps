<?php

namespace Humaidem\FilamentMaps\Concerns;

use Illuminate\Support\Str;

trait HasFilamentMapLocation
{
    public static function getCoordinatesAttributes(): array
    {
        return [
            'latitude' => static::getLatitudeName(),
            'longitude' => static::getLongitudeName(),
        ];
    }

    public static function getComputedLocation(): string
    {
        return static::getFieldName();
    }

    public function setAttribute($key, $value): mixed
    {
        if ($key === static::getFieldName()) {
            $this->attributes[static::getLatitudeName()] = $this->castAttributeAsJson(static::getLatitudeName(), $value['latitude']);
            $this->attributes[static::getLongitudeName()] = $this->castAttributeAsJson(static::getLongitudeName(), $value['longitude']);
            $this->attributes[static::getGeoJsonName()] = $this->castAttributeAsJson(static::getGeoJsonName(), $value['geoJson']);
            unset($this->attributes[static::getFieldName()]);
            return $this;
        }
        return parent::setAttribute($key, $value);
    }

    public static function getLatitudeName(): string
    {
        return 'latitude';
    }

    public static function getLongitudeName(): string
    {
        return 'longitude';
    }

    public function getAttribute($key): mixed
    {
        if ($key === static::getFieldName()) {
            return $this->getFieldDataArray();
        }
        return parent::getAttribute($key);
    }

    private function getFieldDataArray(): array
    {
        return [
            "latitude" => (float)$this->{static::getLatitudeName()},
            "longitude" => (float)$this->{static::getLongitudeName()},
            "geo_json" => $this->{static::getGeoJsonName()} ?? [],
        ];
    }

    public function __call($method, $parameters): mixed
    {
        if (Str::lower($method) === Str::lower("get" . static::getFieldName() . "Attribute")) {
            return $this->getFieldDataArray();
        }
        return parent::__call($method, $parameters);
    }

    protected function initializeHasFilamentMapLocation(): void
    {
        if (!in_array(static::getFieldName(), $this->appends)) {
            $this->appends[] = static::getFieldName();
        }
        if (!in_array(static::getGeoJsonName(), array_keys($this->casts))) {
            $this->casts[static::getGeoJsonName()] = 'array';
        }
    }

    public static function getFieldName(): string
    {
        return 'location';
    }

    public static function getGeoJsonName(): string
    {
        return 'geo_json';
    }

}
