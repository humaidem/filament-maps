@php use Filament\Support\Facades\FilamentAsset; @endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $statePath = $getStatePath();
    @endphp
    <div
        class="flex"
        x-ignore
        ax-load
        ax-load-css="[@js(FilamentAsset::getStyleHref('filament-maps-field', package: 'humaidem/filament-maps'))]"
        ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('filament-maps-field', 'humaidem/filament-maps') }}"
        x-data="filamentMapsField({
                    state: $wire.entangle('{{ $getStatePath() }}'),
                    setStateUsing: (path, state) => {
                        return $wire.set(path, state)
                    },
                    getStateUsing: (path) => {
                        return $wire.get(path)
                    },
                    statePath: @js($getStatePath()),
                    allowFullscreen: @js($getAllowFullscreen()),
                    tileLayerUrl: @js($getTileLayerUrl()),
                    maxZoom: @js($getMaxZoom()),
                    subDomains: @js($getSubDomains()),
                    markerIcon: @js($getMarkerIcon()),
                    MarkerShadowIcon: @js($getMarkerShadowIcon()),
                    maxMarkers: @js($getMaxMarkers()),
                    canDrawMarker: @js($getCanDrawMarker()),
                    canDrawPolygon: @js($getCanDrawPolygon()),
                    canDrawPolygonLine: @js($getCanDrawPolygonLine()),
                    canDrawRectangle: @js($getCanDrawRectangle()),
                    canDrawCircle: @js($getCanDrawCircle()),
                    canDrawCircleMarker: @js($getCanDrawCircleMarker()),
                    boundary: @js($getBoundary()),
                    mapEl: $refs.map
                })"
        id="{{ $getId() . '-alpine' }}"
        wire:ignore
    >
        <div
            x-ref="map"
            class="w-full"
            style="
                height: {{ $getHeight() }};
                min-height: 30vh;
                z-index: 1 !important;
            "
        ></div>
    </div>
</x-dynamic-component>
