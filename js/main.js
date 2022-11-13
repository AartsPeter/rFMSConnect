
var map = L.map('map', {drawControl: true}).setView([51.505, 5.409], 13);
TomTom = L.tileLayer('https://{s}.api.tomtom.com/map/1/tile/basic/{style}/{z}/{x}/{y}.{ext}?key={apikey}&language={language}', {
    attribution: '<a href="tomtom.com" target="_blank">&copy;  1992 - 2021 TomTom.</a>',subdomains: 'abcd',apikey: 'qlmH59sLZa3TDpqBwQRxFh4wRNz0zpuw',style: 'main',maxZoom: 22,language: 'en-GB',ext: 'png',	size: '512'
});
map.addLayer(TomTom);
var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

map.addControl(new L.Control.Draw({
    edit: {
        featureGroup: drawnItems,
        poly: {
            allowIntersection: false
        }
    },
    draw: {
        polygon: {
            allowIntersection: false,
        }
    }
}));

map.on(L.Draw.Event.CREATED, function (event) {
    var layer = event.layer;
    drawnItems.addLayer(layer);
});

document.getElementById('export').onclick = function(e) {
    // Extract GeoJson from featureGroup
    var data = drawnItems.toGeoJSON();

    // Stringify the GeoJson
    var convertedData = 'text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data));

    // Create export
    document.getElementById('export').setAttribute('href', 'data:' + convertedData);
    document.getElementById('export').setAttribute('download','data.geojson');
}