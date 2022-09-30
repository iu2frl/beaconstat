<?php
require_once './connect.php';
require_once './functions.php';

define("RECAPTCHA_V3_SECRET_KEY", $captchaSecretKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['callsign']) || $_POST['callsign']) {
        if (isset($_POST['locator']) || $_POST['locator']) {
            if (isset($_POST['beacon_id']) || $_POST['beacon_id']) {
                if (isset($_POST['data']) || $_POST['data']) {
                    if (isset($_POST['antenna']) || $_POST['antenna']) {
                        if (isset($_POST['note']) || $_POST['note']) {
                            $callsign = strtoupper(htmlspecialchars(strval($_POST["callsign"])));
                            $locator = strtoupper(htmlspecialchars(strval($_POST["locator"])));
                            $antenna = htmlspecialchars(strval($_POST["antenna"]));
                            $beacon_id = htmlspecialchars(strval($_POST["beacon_id"]));
                            $data = htmlspecialchars(strval($_POST["data"]));
                            $note = htmlspecialchars(strval($_POST["note"]));
                            if (isset($_POST["heard"])) {
                                $status = isset($_POST["heard"]) ? 1 : 0;
                            } else {
                                $status = (int)0;
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    // set error message and redirect back to form...
    WaitForPopup('Some parameters were empty, please try again', './index.php');
}

if ($callsign == '' or $locator == '' or $beacon_id == '' or $data == '' or $antenna == '') {
    WaitForPopup("Ricevuto un campo vuoto, tornare indietro e riprovare", './index.php');
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

    //TODO: SQL-injection protection da collaudare
    $stmt = $db->prepare("INSERT INTO `bs_report` (`callsign`, `locator`, `beacon_id`, `data`, `antenna`, `status`, `note`) VALUES (?,?,?,?,?,?,?)");
    if ($stmt == FALSE) {
        WaitForPopup("Errore nella preparazione della QUERY", './index.php');
    } else {
        $statusVal = intval($status);
        $stmt->bind_param('ssissis', $callsign, $locator, $beacon_id, $data, $antenna, $statusVal, $note);
        if ($stmt == FALSE) {
            WaitForPopup("Errore nel binding della QUERY", './index.php');
        } else {
            $stmt->execute();
        }
    }

    // controllo l'esito
    if (!$stmt) {
        // die("Errore nella query $query: " . mysqli_error());
        WaitForPopup("Errore nella esecuzione della QUERY", './index.php');
    } else {
        echo "<p>Please wait...</p>";
        SendTelegramMessage(
            "Report for '" . $beacon_id . "' was entered by " . $_SERVER['REMOTE_ADDR'] . "\n\n" .
            "Reporter: " . $callsign . "\n" .
            "Locator: " . $locator . "\n" .
            "Beacon ID: " . $beacon_id . "\n" .
            "Data: " . $data . "\n" .
            "Antenna: " . $antenna . "\n" .
            "Status: " . $statusVal . "\n" .
            "Note: " . $note
        );
        WaitForPopup('Report inviato correttamente', './index.php');
    }

    // chiudo la connessione a MySQL
    mysqli_close($db);
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
        WaitForPopup("User or Robot verification failed, please try again!\nDebug: " . $response, './index.php');
    }
}
