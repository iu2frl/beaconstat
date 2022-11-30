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

        // Posizione iniziale della mappa
        var mapLat = 42.000;
        var mapLon = 13.000;
        var mapZoom = 6;

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

            var vectorLayer = new OpenLayers.Layer.Vector("Overlay");
            var beaconList = [];

            <?php
            require_once './functions.php';
            require_once './connect.php';
            // Prepare empty title
            $mapTitle = "";
            // Define if to show general map or specific one
            $genMap = true;
            // Check PHP values from GET
            $call =  isset($_GET['call']) ? $_GET['call'] : '';
            $grid =  isset($_GET['loc']) ? $_GET['loc'] : '';

            if (($call == "") && ($grid == "")) {
                $genMap = true;
            } else {
                $genMap = false;
            }

            if ($genMap) {
                $stmt = $db->prepare("SELECT `locator`, `qrg`, `callsign`, `band`, `status` FROM `bs_beacon`");
                if ($stmt == FALSE) {
                    WaitForPopup("Error preparing query", "./index.php");
                } else {
                    if ($stmt == FALSE) {
                        WaitForPopup("Error binding query", "./index.php");
                    } else {
                        $stmt->execute();
                    }
                }

                // controllo l'esito
                if (!$stmt) {
                    WaitForPopup("Error executing query", "./index.php");
                }

                //lista di dizionari con dati dei beacon usata in seguito per creare i marker
                $b_result = $stmt->get_result();

                if (!$b_result) {
                    echo "<tr><td colspan='14'>Il database ha riportato ERRORE</td></tr>";
                } else {
                    if (mysqli_num_rows($b_result) > 0) {
                        //nuova versione marker
                        while ($row = mysqli_fetch_assoc($b_result)) {
                            if ($row["locator"] != "") {
                                echo "beaconList.push({locator:'" . $row["locator"] . "', call:'" . $row["callsign"] . "', qrg: '" . $row["qrg"] . "', band: '" . $row["band"] . "', status: '" . $row["status"] . "'});\n";
                            } else {
                                // Questo beacon ha locatore invalido, scriviamo qualcosa a log?
                            }
                        }
                    }
                }
            } else {
                $mapTitle = "<h1 style='font-family: Arial; font-weight: bold; text-align: center'>Beacon: ";
                if ($call == '') {
                    $mapTitle .= "no-name";
                } else {
                    $mapTitle .= $call;
                }
                if ($grid != '') {
                    $mapTitle .= " - Grid Square: " . $grid;
                } else {
                    WaitForPopup("Invalid locator, returning to index", "./index.php");
                }
                $mapTitle .= "</h1>";
                echo "beaconList.push({locator:'" . $grid . "', call:'" . $call . "', qrg: '', band: '', status: ''});\n";
            }
            ?>

            //creo i marker con i dati del beacon che rappresentano 
            for (var i = 0; i < beaconList.length; i++) {
                var appGridBcn = (beaconList[i]['locator'].substr(0, 2)).toUpperCase() + beaconList[i]['locator'].substr(2, 2) + (beaconList[i]['locator'].substr(4, 2)).toLowerCase();
                if ((/[a-zA-Z]/.test(appGridBcn.substr(0, 2))) && (/[0-9]/.test(appGridBcn.substr(2, 2))) && (/[a-zA-Z]/.test(appGridBcn.substr(4, 2)))) {
                    var coorBcn = gridSquareToLatLon(appGridBcn)
                    <?php
                        if ($genMap) {
                            echo "var eGraphic = 'img/m' + beaconList[i]['band'] + '.svg';";
                        } else {
                            echo "var eGraphic = 'img/marker.svg';";
                        }
                    ?>
                    var feature = new OpenLayers.Feature.Vector(
                        new OpenLayers.Geometry.Point(coorBcn[1], coorBcn[0]).transform(new OpenLayers.Projection('EPSG:4326'), map.getProjectionObject()), {
                            <?php
                            if ($genMap) {
                                echo "description: '<strong>' + beaconList[i]['call'] + '</strong><br>QRG: ' + beaconList[i]['qrg'].replace('.', ',') + ' MHz<br>Locator: ' + beaconList[i]['locator'] + '<br>Stato: ' + ((beaconList[i]['status'] == 1) ? 'Attivo' : 'Non Attivo')";
                            } else {
                                echo "description: '<strong>' + beaconList[i]['call'] + '</strong><br>Locator: ' + beaconList[i]['locator']";
                            }
                            ?>,
                        },
                        {
                            externalGraphic: eGraphic,
                            graphicHeight: 25,
                            graphicWidth: 21,
                            graphicXOffset: -12,
                            graphicYOffset: -25
                        }
                    );
                    vectorLayer.addFeatures(feature);
                } else {
                    console.log("Beacon " + beaconList[i]['call'] + " will be ignored");
                }
            }

            //aggiungo layer dei beacon
            map.addLayer(vectorLayer);

            //creao controllo per gestione pop-up dei singoli marker
            var controls = {
                selector: new OpenLayers.Control.SelectFeature(vectorLayer, {
                    onSelect: createPopup,
                    onUnselect: destroyPopup
                })
            };

            //callback per gestire apertura/chiusura dei pop dei singoli beacon
            function createPopup(feature) {
                feature.popup = new OpenLayers.Popup.FramedCloud("pop",
                    feature.geometry.getBounds().getCenterLonLat(),
                    null,
                    '<div class="markerContent">' + feature.attributes.description + '</div>',
                    null,
                    true,
                    function() {
                        controls['selector'].unselectAll();
                    }
                );

                map.addPopup(feature.popup);
            }

            function destroyPopup(feature) {
                feature.popup.destroy();
                feature.popup = null;
            }

            //registro callback e le attivo
            map.addControl(controls['selector']);
            controls['selector'].activate();
        }
    </script>
</head>

<body onload="init();">
    <?php
    echo $mapTitle;
    WriteMapHeader();
    ?>
    <div style="width:100%; height:85%" id="map"></div>
    <table>
        <tr>
            <td>
                <a href="./index.php" class="button"><br>Back</a>
            </td>
        </tr>
    </table>
    <?php WriteMapFooter(); ?>
</body>

</html>