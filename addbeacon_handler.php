<?php

require_once './connect.php';
require_once './functions.php';

define("RECAPTCHA_V3_SECRET_KEY", $captchaSecretKey);

if (isset($_POST['callsign']) || $_POST['callsign']) {
    if (isset($_POST['locator']) || $_POST['locator']) {
        if (isset($_POST['qrg']) || $_POST['qrg']) {
            if (isset($_POST['qth']) || $_POST['qth']) {
                if (isset($_POST['asl']) || $_POST['asl']) {
                    if (isset($_POST['antenna']) || $_POST['antenna']) {
                        if (isset($_POST['mode']) || $_POST['mode']) {
                            if (isset($_POST['qtf']) || $_POST['qtf']) {
                                if (isset($_POST['power']) || $_POST['power']) {
                                    $callsign = strtoupper(htmlspecialchars(strval($_POST["callsign"])));
                                    $locator = strtoupper(htmlspecialchars(strval($_POST["locator"])));
                                    $qrg = (float)$_POST["qrg"];
                                    $qth = htmlspecialchars(strval($_POST["qth"]));
                                    $asl = intval($_POST["asl"]);
                                    $antenna = htmlspecialchars(strval($_POST["antenna"]));
                                    $mode = htmlspecialchars(strval($_POST["mode"]));
                                    $qtf = htmlspecialchars(strval($_POST["qtf"]));
                                    $power = (float)$_POST["power"];
                                    if (isset($_POST['status'])) {
                                        $status = isset($_POST["status"]) ? 1 : 0;
                                    } else {
                                        $status = (int)0;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    // set error message and redirect back to form...
    WaitForPopup('Some parameters were empty, please try again', 'addbeacon.php');
}

$token = $_POST['token'];
$action = $_POST['action'];

// call curl to POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$arrResponse = json_decode($response, true);

// verify the response
if ($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
    // valid submission
    // go ahead and do necessary stuff

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($callsign == '' or $locator == '' or $qrg == '' or $qth == '') {
            if ($debug == true) {
                echo "<html><body>";
                echo "<p>Call: ", $callsign,  "</p>";
                echo "<p>Locator: ", $locator,  "</p>";
                echo "<p>QRG: ", $qrg,  "</p>";
                echo "<p>QTH: ", $qth,  "</p>";
                echo "<p>ASL: ", $asl,  "</p>";
                echo "<p>Antenna: ", $antenna,  "</p>";
                echo "<p>Mode: ", $mode,  "</p>";
                echo "<p>Qtf: ", $qtf,  "</p>";
                echo "<p>Power: ", $power,  "</p>";
                echo "<p>Status: ", $status,  "</p>";
                echo "</body></html>";
                die("Ricevuto un campo vuoto, tornare indietro e riprovare");
            } else {
                WaitForPopup('Some parameters were empty, please try again', 'addbeacon.php');
            }
        }

        $band = floor($qrg);
        $band_list = array('50', '144', '432', '1296', '2320', '5760', '10368', '24048');

        if (!in_array($band, $band_list, FALSE)) {
            WaitForPopup("Specified band is invalid, please try again", 'addbeacon.php');
        }

        //TODO: SQL-injection protection da collaudare
        $band = intval($band);
        var_dump($band);

        $stmt = $db->prepare("INSERT INTO `bs_beacon` (`callsign`, `locator`, `qrg`, `band`, `qth`, `asl`, `antenna`, `mode`, `qtf`, `power`, `status`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        if ($stmt == FALSE) {
            WaitForPopup("Error while preparing INSERT query, please try again", 'addbeacon.php');
        } else {
            $intValBand = intval($band);
            $intValStatus = intval($status);
            $stmt->bind_param('ssdisisssdi', $callsign, $locator, $qrg, $band, $qth, $asl, $antenna, $mode, $qtf, $power, $intValStatus);
            if ($stmt == FALSE) {
                WaitForPopup("Error while binding query data, please try again", 'addbeacon.php');
            } else {
                $stmt->execute();
            }
        }

        // controllo l'esito
        if (!$stmt) {
            // die("Errore nella query $query: " . mysqli_error());
            WaitForPopup("Error executing query, please try again", 'addbeacon.php');
        } else {
            echo "<p>Please wait...</p>";
            SendTelegramMessage("Beacon '" . $callsign . "' was added to DB by " . $_SERVER['REMOTE_ADDR'] . "\n\n" .
                "Locator: " . $locator . "\n" .
                "QRG: " . $qrg . "\n" .
                "Band: " . $band . "\n" .
                "QTH: " . $qth . "\n" .
                "ASL: " . $asl . "\n" .
                "Antenna: " . $antenna . "\n" .
                "Mode: " . $mode . "\n" .
                "QTF: " . $qtf . "\n" .
                "Power: " . $power . "\n" .
                "Status: " . $intValStatus . "\n"
            );
            WaitForPopup("Beacon was successfully saved!", 'index.php');
        }

        // chiudo la connessione a MySQL
        mysqli_close($db);
    }
} else {
    // spam submission
    // show error message
    if ($debug == true) {
        echo "<html><body>";
        echo "<p>Token: ", $token,  "</p>";
        echo "<p>Action: ", $action,  "</p>";
        echo "<p>Error code: ", implode(", ", $arrResponse["error-codes"]),  "</p>";
        //echo "<p>Success: ", $arrResponse["success"],  "</p>";
        echo "<p>Response: ", $response,  "</p>";
        echo "</body></html>";
        die("Captcha failed");
    } else {
        WaitForPopup("User or Robot verification failed, please try again!", 'addbeacon.php');
    }
}
