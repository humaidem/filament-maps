<?php

namespace Humaidem\FilamentMaps\Fields;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\URL;

class Map extends Field
{
    protected string $view = 'filament-maps::fields.filament-maps-field';

    protected Closure|string $height = '350px';
    protected Closure|string $tileLayerUrl = 'https://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}';
    protected Closure|int $maxZoom = 22;
    protected Closure|array $subDomains = ['mt0', 'mt1', 'mt2', 'mt3'];
    protected Closure|bool $allowFullscreen = false;
    protected Closure|string|null $markerIcon = null;
    protected Closure|string|null $markerShadowIcon = null;
    protected Closure|bool $canDrawMarker = false;
    protected Closure|bool $canDrawPolygon = false;
    protected Closure|int $maxMarkers = 1;
    protected Closure|array|null $boundary = null;
    protected Closure|bool $canDrawPolygonLine = false;
    protected Closure|bool $canDrawRectangle = false;
    protected Closure|bool $canDrawCircle = false;
    protected Closure|bool $canDrawCircleMarker = false;

    /**
     * Set Map height
     *
     * @param Closure|string $height
     * @return self
     */
    public function height(Closure|string $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get Map width
     *
     * @return string
     */
    public function getHeight(): string
    {
        return $this->evaluate($this->height);
    }

    /**
     * Set allowFullscreen
     *
     * @param Closure|bool $allowFullscreen
     * @return self
     */
    public function allowFullscreen(Closure|bool $allowFullscreen = true): self
    {
        $this->allowFullscreen = $allowFullscreen;

        return $this;
    }

    /**
     * Get allowFullscreen
     *
     * @return bool
     */
    public function getAllowFullscreen(): bool
    {
        return $this->evaluate($this->allowFullscreen);
    }

    /**
     * Set tileLayerUrl
     *
     * @param Closure|bool $tileLayerUrl
     * @return self
     */
    public function tileLayerUrl(Closure|bool $tileLayerUrl = true): self
    {
        $this->tileLayerUrl = $tileLayerUrl;

        return $this;
    }

    /**
     * Get tileLayerUrl
     *
     * @return string
     */
    public function getTileLayerUrl(): string
    {
        return $this->evaluate($this->tileLayerUrl);
    }

    /**
     * Set tileLayerUrl
     *
     * @param Closure|int $maxZoom
     * @return self
     */
    public function maxZoom(Closure|int $maxZoom): self
    {
        if ($maxZoom > 22) $maxZoom = 22;
        $this->maxZoom = $maxZoom;

        return $this;
    }

    /**
     * Get tileLayerUrl
     *
     * @return int
     */
    public function getMaxZoom(): int
    {
        $maxZoom = $this->evaluate($this->maxZoom);
        if ($maxZoom > 22)
            return 22;
        return $maxZoom;
    }

    /**
     * Set subDomains
     *
     * @param Closure|array $subDomains
     * @return self
     */
    public function subDomains(Closure|array $subDomains): self
    {
        $this->subDomains = $subDomains;

        return $this;
    }

    /**
     * Get subDomains
     *
     * @return array
     */
    public function getSubDomains(): array
    {
        return $this->evaluate($this->subDomains);
    }

    /**
     * Set markerIcon
     *
     * @param Closure|string $markerIcon
     * @return self
     */
    public function markerIcon(Closure|string $markerIcon): self
    {
        $this->markerIcon = $markerIcon;

        return $this;
    }

    /**
     * Get getMarkerIcon
     *
     * @return string
     */
    public function getMarkerIcon(): string
    {
        if ($this->markerIcon == null)
            return Url::assetFrom('', 'humaidem/filament-maps/marker-icon.png');

        return $this->evaluate($this->markerIcon);
    }

    /**
     * Set markerShadowIcon
     *
     * @param Closure|string $markerShadowIcon
     * @return self
     */
    public function markerShadowIcon(Closure|string $markerShadowIcon): self
    {
        $this->markerShadowIcon = $markerShadowIcon;

        return $this;
    }

    /**
     * Get getMarkerShadowIcon
     *
     * @return string
     */
    public function getMarkerShadowIcon(): string
    {
        if ($this->markerShadowIcon == null)
            return Url::assetFrom('', 'humaidem/filament-maps/marker-shadow.png');

        return $this->evaluate($this->markerShadowIcon);
    }

    /**
     * Set maxMarkers
     *
     * @param Closure|int $maxMarkers
     * @return self
     */
    public function maxMarkers(Closure|int $maxMarkers): self
    {
        $this->maxMarkers = $maxMarkers;

        return $this;
    }

    /**
     * Get getMaxMarkers
     *
     * @return int
     */
    public function getMaxMarkers(): int
    {
        return $this->evaluate($this->maxMarkers);
    }

    /**
     * Set canDrawMarker
     *
     * @param Closure|bool $canDrawMarker
     * @return self
     */
    public function canDrawMarker(Closure|bool $canDrawMarker = true): self
    {
        $this->canDrawMarker = $canDrawMarker;

        return $this;
    }

    /**
     * Get getCanDrawMarker
     *
     * @return bool
     */
    public function getCanDrawMarker(): bool
    {
        return $this->evaluate($this->canDrawMarker);
    }

    /**
     * Set canDrawPolygon
     *
     * @param Closure|bool $canDrawPolygon
     * @return self
     */
    public function canDrawPolygon(Closure|bool $canDrawPolygon = true): self
    {
        $this->canDrawPolygon = $canDrawPolygon;

        return $this;
    }

    /**
     * Get getCanDrawPolygon
     *
     * @return bool
     */
    public function getCanDrawPolygon(): bool
    {
        return $this->evaluate($this->canDrawPolygon);
    }

    /**
     * Set canDrawPolygonLine
     *
     * @param Closure|bool $canDrawPolygonLine
     * @return self
     */
    public function canDrawPolygonLine(Closure|bool $canDrawPolygonLine = true): self
    {
        $this->canDrawPolygonLine = $canDrawPolygonLine;

        return $this;
    }

    /**
     * Get getCanDrawPolygonLine
     *
     * @return bool
     */
    public function getCanDrawPolygonLine(): bool
    {
        return $this->evaluate($this->canDrawPolygonLine);
    }

    /**
     * Set canDrawRectangle
     *
     * @param Closure|bool $canDrawRectangle
     * @return self
     */
    public function canDrawRectangle(Closure|bool $canDrawRectangle = true): self
    {
        $this->canDrawRectangle = $canDrawRectangle;

        return $this;
    }

    /**
     * Get getCanDrawRectangle
     *
     * @return bool
     */
    public function getCanDrawRectangle(): bool
    {
        return $this->evaluate($this->canDrawRectangle);
    }

    /**
     * Set canDrawCircle
     *
     * @param Closure|bool $canDrawCircle
     * @return self
     */
    public function canDrawCircle(Closure|bool $canDrawCircle = true): self
    {
        $this->canDrawCircle = $canDrawCircle;

        return $this;
    }

    /**
     * Get getCanDrawCircle
     *
     * @return bool
     */
    public function getCanDrawCircle(): bool
    {
        return $this->evaluate($this->canDrawCircle);
    }

    /**
     * Set canDrawCircleMarker
     *
     * @param Closure|bool $canDrawCircleMarker
     * @return self
     */
    public function canDrawCircleMarker(Closure|bool $canDrawCircleMarker = true): self
    {
        $this->canDrawCircleMarker = $canDrawCircleMarker;

        return $this;
    }

    /**
     * Get getCanDrawCircleMarker
     *
     * @return bool
     */
    public function getCanDrawCircleMarker(): bool
    {
        return $this->evaluate($this->canDrawCircleMarker);
    }


    /**
     * Set boundary
     *
     * @param Closure|array $boundary
     * @return self
     */
    public function boundary(Closure|array $boundary): self
    {
        $this->boundary = $boundary;

        return $this;
    }

    /**
     * Get getBoundary
     *
     * @return array|null
     */
    public function getBoundary(): array|null
    {
        return $this->evaluate($this->boundary);
    }
}
