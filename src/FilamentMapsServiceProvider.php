<?php

namespace Humaidem\FilamentMaps;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\AssetManager;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMapsServiceProvider extends PackageServiceProvider
{
    protected array $widgets = [];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-maps')
            ->hasRoutes(['web'])
//            ->hasConfigFile()
//            ->hasTranslations()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
//        $this->mergeConfigFrom(__DIR__ . '/../config/filament-maps.php', 'filament-maps');
        $this->app->resolving(AssetManager::class, function () {
            FilamentAsset::register([
                AlpineComponent::make('filament-maps-field', __DIR__ . '/../dist/humaidem/filament-maps/filament-maps-field.js'),
//                Js::make('filament-maps-field-js', __DIR__ . '/../dist/humaidem/filament-maps/filament-maps-field.js')->loadedOnRequest(),
                Css::make('filament-maps-field', __DIR__ . '/../dist/humaidem/filament-maps/filament-maps-field.css'),
//                AlpineComponent::make('filament-maps-field-css', __DIR__ . '/../dist/humaidem/filament-maps/filament-maps-field.css'),
            ], 'humaidem/filament-maps');
        });
    }
}
