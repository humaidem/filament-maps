<?php

namespace Humaidem\FilamentMaps\Facades;

class FilamentMapMethods
{
    public function mergeGeoJSONFeatureCollections($featureCollections): array
    {
        if ($featureCollections == null)
            return [];

        $mergedFeatures = [];

        foreach ($featureCollections as $featureCollection) {
            if ($featureCollection['type'] === 'FeatureCollection' && isset($featureCollection['features'])) {
                foreach ($featureCollection['features'] as $feature) {
                    $mergedFeatures[] = $feature;
                }
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $mergedFeatures
        ];
    }
}
