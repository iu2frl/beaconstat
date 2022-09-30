<html lang="en">
<?php
include_once "./config/bs_config.php";
?>

<head>
    <title><?php echo $masterSiteName ?></title>
    <link rel="stylesheet" type="text/css" href="./main.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.13.1/OpenLayers.js"></script>
    <script type="text/javascript">
        gridSquareToLatLon = function(grid, obj) {
            var returnLatLonConstructor = (typeof(LatLon) === 'function');
            var returnObj = (typeof(obj) === 'object');
            var lat = 0.0,
                lon = 0.0,
                aNum = "a".charCodeAt(0),
                numA = "A".charCodeAt(0);

            function lat4(g) {
                return 10 * (g.charCodeAt(1) - numA) + parseInt(g.charAt(3)) - 90;
            }

            function lon4(g) {
                return 20 * (g.charCodeAt(0) - numA) + 2 * parseInt(g.charAt(2)) - 180;
            }
            if ((grid.length != 4) && (grid.length != 6)) throw "gridSquareToLatLon: grid must be 4 or 6 chars: " + grid;
            if (/^[A-X][A-X][0-9][0-9]$/.test(grid)) {
                lat = lat4(grid) + 0.5;
                lon = lon4(grid) + 1;
            } else if (/^[A-X][A-X][0-9][0-9][a-x][a-x]$/.test(grid)) {
                lat = lat4(grid) + (1.0 / 60.0) * 2.5 * (grid.charCodeAt(5) - aNum + 0.5);
                lon = lon4(grid) + (1.0 / 60.0) * 5 * (grid.charCodeAt(4) - aNum + 0.5);
            } else throw "gridSquareToLatLon: invalid grid: " + grid;
            if (returnLatLonConstructor) return new LatLon(lat, lon);
            if (returnObj) {
                obj.lat = lat;
                obj.lon = lon;
                return obj;
            }
            return [lat, lon];
        };

        var queryString = window.location.search;
        //console.log(queryString);
        var urlParams = new URLSearchParams(queryString);
        //console.log(urlParams);
        var gridBcn = urlParams.get('loc');
        var callBcn = urlParams.get('call');
        console.log(gridBcn);
        console.log(callBcn);

        var appGridBcn = (gridBcn.substr(0, 2)).toUpperCase() + gridBcn.substr(2, 2) + (gridBcn.substr(gridBcn.length - 2)).toLowerCase();

        var coordBcn = gridSquareToLatLon(appGridBcn);
        //console.log(coordBcn);
        var bcnLat = coordBcn[0];
        var bcnLon = coordBcn[1];

        // Posizione iniziale della mappa
        var mapLat = bcnLat;
        console.log(bcnLat);
        var mapLon = bcnLon;
        console.log(bcnLon);
        var mapZoom = 10;

        function init() {

            map = new OpenLayers.Map("map", {
                controls: [
                    new OpenLayers.Control.Navigation(),
                    new OpenLayers.Control.PanZoomBar(),
                    new OpenLayers.Control.ScaleLine(),
                    new OpenLayers.Control.Permalink('permalink'),
                    new OpenLayers.Control.MousePosition(),
                    new OpenLayers.Control.Attribution()
                ],
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326")
            });

            var mapnik = new OpenLayers.Layer.OSM("OpenStreetMap (Mapnik)");

            map.addLayer(mapnik);

            var lonLat = new OpenLayers.LonLat(mapLon, mapLat).transform(
                new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                map.getProjectionObject() // to Spherical Mercator Projection
            );

            map.setCenter(lonLat, mapZoom);

            var point = new OpenLayers.LonLat(bcnLon, bcnLat).transform(
                new OpenLayers.Projection("EPSG:4326"),
                map.getProjectionObject());

            // Not sure this works
            var size = new OpenLayers.Size(21, 25);
            var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);
            var icon = new OpenLayers.Icon('./img/marker.svg', size, offset);
            var markers = new OpenLayers.Layer.Markers("Markers");
            map.addLayer(markers);

            markers.addMarker(new OpenLayers.Marker(point), icon);

        }
    </script>
</head>

<body onload="init();">
    <?php
    require_once './functions.php';
    // Cerca la banda di cui fare la query
    $call =  isset($_GET['call']) ? $_GET['call'] : '';
    $grid =  isset($_GET['loc']) ? $_GET['loc'] : '';
    echo "<h1 style='font-family: Arial; font-weight: bold; text-align: center'>";
    if ($call == '') {
        echo "no-name";
    } else {
        echo "Beacon: " . $call;
    }
    if ($grid != '') {
        echo " - Grid Square: " . $grid;
    }
    echo "</h1>";
    ?>
    <?php WriteMapHeader(); ?>
    <div style="width:100%; height:85%" id="map"></div>
    <?php WriteMapFooter(); ?>
</body>

</html>