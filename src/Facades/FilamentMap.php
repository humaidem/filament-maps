<?php /** @noinspection PhpUnused */

namespace Humaidem\FilamentMaps\Facades;

use Illuminate\Support\Facades\Facade;

class FilamentMap extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FilamentMapMethods::class;
    }
}
