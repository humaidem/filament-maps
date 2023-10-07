import L from 'leaflet';
import 'leaflet-draw';
import 'leaflet-fullscreen';
import "leaflet/dist/leaflet.css";
import "leaflet-fullscreen/dist/leaflet.fullscreen.css";
import "leaflet-draw/dist/leaflet.draw.css";
import {area, center} from "@turf/turf";
import {v4 as uuidv4} from 'uuid';


window.type = true;
export default function filamentMapsField(
    {
        state,
        statePath,
        setStateUsing,
        getStateUsing,
        mapEl,
        allowFullscreen,
        tileLayerUrl,
        maxZoom,
        subDomains,
        markerIcon,
        markerShadowIcon,
        maxMarkers,
        canDrawMarker,
        canDrawPolygon,
        canDrawPolygonLine,
        canDrawRectangle,
        canDrawCircle,
        canDrawCircleMarker,
        boundary,
    }
) {
    return {
        state,
        map: null,
        tileLayer: null,
        drawControl: null,
        drawnItems: null,
        boundaryControl: null,
        boundaryItems: null,
        cachedLayers: {},
        init: function () {
            this.addLayerProperties();
            this.initMap();
            this.initTileLayer();
            this.initBoundary();
            this.initDrawLayers();
            this.initDrawToolbar();
            this.initMapEvents();
            this.updateState();
        },
        initMap() {
            const state = getStateUsing(statePath);
            this.map = new L.Map(mapEl, {
                center: [state?.latitude ?? 24.462512, state?.longitude ?? 54.3696599],
                zoom: 16,
                markerZoomAnimation: false,
                zoomControl: false,
                attributionControl: false,
                fullscreenControl: allowFullscreen,
            });

            L.Marker.prototype.options.icon = L.icon({
                iconUrl: markerIcon,
                shadowUrl: markerShadowIcon,
                iconSize: [24, 40],
                iconAnchor: [12, 20]
            });
        },
        initTileLayer() {
            this.tileLayer = L.tileLayer(tileLayerUrl, {
                maxZoom: maxZoom,
                subdomains: subDomains,
                type: 'map',
            });
            this.map.addLayer(this.tileLayer);
        },
        initMapEvents() {
            this.map.on('draw:created', (e) => this.drawCreated(e));
            this.map.on('draw:editstart', (e) => this.drawEditStart(e));
            this.map.on('draw:editstop', (e) => this.drawEditStop(e));
            this.map.on('draw:edited', (e) => this.drawEdited(e));
            this.map.on('draw:deleted', (e) => this.drawDeleted(e));
        },
        initDrawLayers() {

            this.drawnItems = new L.FeatureGroup();
            this.map.addLayer(this.drawnItems);

            const state = getStateUsing(statePath);
            if (state == null)
                return;

            L.geoJSON(state?.geo_json ?? {}).getLayers().forEach(layer => {
                this.drawnItems.addLayer(layer);
            });

        },
        isWithinBoundary(layer) {
            return true;
            // const feature = boundary.features.find(feature => {
            //     return turf.booleanWithin(layer.toGeoJSON(11), feature.geometry);
            // });
            // return !!feature;
        },
        initDrawToolbar() {
            if (this.drawControl)
                this.map.removeControl(this.drawControl);


            this.drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: this.drawnItems
                },
                draw: {
                    marker: this.canDrawMarker(),
                    polyline: this.canDrawPolygonLine(),
                    polygon: this.canDrawPolygon(),
                    rectangle: this.canDrawRectangle(),
                    circle: this.canDrawCircle(),
                    circlemarker: this.canDrawCircleMarker()
                }
            });
            this.map.addControl(this.drawControl);
        },
        drawCreated(e) {

            if (boundary && !this.isWithinBoundary(e.layer)) {
                return;
            }

            e.layer.setGeoJSONProperties({
                uuid: this.getUUID(),
                area: area(e.layer.toGeoJSON(11))
            });

            this.drawnItems.addLayer(e.layer);
            this.updateState();
            this.initDrawToolbar();
        },
        drawEditStart(e) {
            this.cachedLayers = this.drawControl.options.edit.featureGroup.toGeoJSON(11);
        },
        drawEditStop(e) {
            this.cachedLayers = null;
        },
        drawEdited(e) {
            this.updateState();
            this.initDrawToolbar();
        },
        drawDeleted(e) {
            this.updateState();
            this.initDrawToolbar();
        },
        canDrawMarker() {
            if (!canDrawMarker || (maxMarkers > 0 && this.getMarkersCount() >= maxMarkers)) {
                return false;
            }

            const customMarker = L.Icon.extend({
                options: {
                    iconUrl: markerIcon,
                    shadowUrl: markerShadowIcon,
                    iconAnchor: new L.Point(12, 20),
                    iconSize: new L.Point(24, 40)
                }
            });

            return {
                icon: new customMarker() //Here assign your custom marker
            }
        },
        canDrawPolygon() {
            if (!canDrawPolygon) {
                return false;
            }
            return {
                shapeOptions: {
                    color: 'yellow',
                }
            };
        },
        canDrawPolygonLine() {
            return canDrawPolygonLine;
        },
        canDrawRectangle() {
            return canDrawRectangle;
        },
        canDrawCircle() {
            return canDrawCircle;
        },
        canDrawCircleMarker() {
            return canDrawCircleMarker;
        },
        updateState() {

            if (this.getLayersCount() === 0) {
                setStateUsing(statePath, null)
                return;
            }

            let data = {latitude: 0, longitude: 0, geoJson: {}};

            if (this.getMarkersCount() > 0) {
                const marker = this.getMarkers();
                data.latitude = marker.getLatLng().lat;
                data.longitude = marker.getLatLng().lng;
            } else {
                const coordinates = center(this.drawnItems.toGeoJSON(11)).geometry.coordinates;
                data.latitude = coordinates[1];
                data.longitude = coordinates[0];
            }
            data.geoJson = this.drawnItems.toGeoJSON();
            console.log(data)
            setStateUsing(statePath, data)
        },
        getMarkers() {
            return this.drawnItems.getLayers().find(function (layer) {
                return layer instanceof L.Marker;
            });
        },
        getMarkersCount() {
            return this.drawnItems.getLayers().filter(function (layer) {
                return layer instanceof L.Marker;
            }).length;
        },
        getLayersCount() {
            return this.drawnItems.getLayers().length;
        },
        initBoundary() {
            if (!boundary)
                return;

            this.boundaryItems = new L.FeatureGroup();
            console.log(boundary)
            // boundary.forEach(feature => {
            //     console.log(feature)
            //     // feature.properties.color = 'red';
            // });
            this.boundaryItems = L.geoJSON(boundary);
            this.map.addLayer(this.boundaryItems);

            this.boundaryControl = new L.Control.Draw({
                edit: {
                    edit: false,
                    remove: false,
                    featureGroup: this.boundaryItems,
                },
                draw: {
                    marker: false,
                    polyline: false,
                    polygon: false,
                    rectangle: false,
                    circle: false,
                    circlemarker: false
                }
            });
            this.map.addControl(this.boundaryControl);
        },
        addLayerProperties() {
            L.Layer.include({
                setGeoJSONProperties: function (properties) {
                    var feature = this.feature = this.feature || {}; // Initialize the feature, if missing.
                    feature.type = 'Feature';
                    feature.properties = properties;
                },
                getGeoJSONProperties: function (properties) {
                    return this.feature && this.feature.properties || {};
                }
            });
        },
        getUUID() {
            return uuidv4();
        },
        getLayerUUID(layer) {
            return layer.toGeoJSON(11).properties.uuid;
        },
        setCachedLayer(uuid, layer) {
            this.cachedLayers.forEach(l => {
                if (l.uuid === uuid) {
                    l.layer = layer;
                }
            });
        },
        getCachedLayer(uuid) {
            return this.cachedLayers.find(layer => {
                return layer.uuid === uuid;
            }).layer;
        }
    }
}
