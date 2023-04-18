<html>

<head>
    <title>CSV Import</title>
    <link rel="stylesheet" type="text/css" href="../../main.css">
</head>

<body>
    <?php
    require_once '../../connect.php';
    require_once '../../functions.php';

    // Ottieni il file più recente
    $xlsxFile = "uploads/" . GetMostRecentFile('uploads', "csv");

    // Controlla se la tabella del DB esiste
    if (!CheckIfTableExists("bs_beacon")) {
        // Crea la tabella
        if (!CreateBsBeaconTable()) {
            WaitForPopup("Unable to create BS_BEACON table", "import.php");
        }
    }

    // Apri file più recente
    $openedFile = fopen($xlsxFile, "r");
    HtmlPrint("Opening: " . $xlsxFile);
    // Preparazione query insert
    $insertStmt = $db->prepare("INSERT INTO bs_beacon (callsign, locator, qrg, band, qth, asl, antenna, mode, qtf, power, status, confirmed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $insertStmt->bind_param('ssdisisssdii', $callsign, $locator, $qrg, $band, $qth, $asl, $antenna, $mode, $qtf, $power, $status, $confirmed);
    // Preparazione query select
    $selectStmt = $db->prepare("SELECT `id` FROM `bs_beacon` WHERE `callsign`=? AND `band`=? ORDER BY `id` DESC LIMIT 1");
    $selectStmt->bind_param('si', $callsign, $band);
    // Preparazione query update
    $updateStmt = $db->prepare("UPDATE bs_beacon SET callsign=?, locator=?, qrg=?, band=?, qth=?, asl=?, antenna=?, mode=?, qtf=?, power=?, status=?, confirmed=? WHERE id=?");
    $updateStmt->bind_param('ssdisisssdiii', $callsign, $locator, $qrg, $band, $qth, $asl, $antenna, $mode, $qtf, $power, $status, $confirmed, $id);

    // Preparazione messaggio Telegram
    $telegramMessage = "";
    // Preparazione contatori statistiche
    $bcnAdded = 0;
    $bcnUpdated = 0;
    $bcnSkipped = 0;

    // Parsing del file CSV
    while (($data = fgetcsv($openedFile)) !== FALSE) {
        if ((sizeof($data) == 9) && (preg_match('~[0-9]+~', $data[1]))) {
            // This line may contain a beacon
            $qrg = floor($data[1] * 1000.0);
            $callsign = substr(strtoupper(strval($data[0])), 0, 10);
            if ($qrg < (float)50.0) {
                // Controllo che la frequenza sia almeno dei 6m
                HtmlPrint("Beacon: " . $callsign . " has frequency below 50MHz (" . htmlspecialchars($qrg) . ") and will be skipped");
                $bcnSkipped += 1;
            } else {
                $band = floor((float)$data[1]);
                $qth = ""; // RSGB does not provide this information
                $locator = substr(strtoupper(strval($data[2])), 0, 6);
                $asl = 0; // RSGB does not provide this information
                $antenna = ""; // RSGB does not provide this information
                $mode = ""; // RSGB does not provide this information
                $qtf = ""; // RSGB does not provide this information
                $power = 0; // RSGB does not provide this information
                $statusStr = strtolower(strval($data[5])); // aggiungere controlli qui
                // Controllo dello stato del beacon
                if (str_contains($statusStr, "operational")) {
                    $status = true;
                    $confirmed = true;
                } else if (str_contains($statusStr, "uncertain")) {
                    $status = true;
                    $confirmed = false;
                } else if (str_contains($statusStr, "dead")) {
                    $status = false;
                    $confirmed = true;
                } else if (str_contains($statusStr, "offline")) {
                    $status = false;
                    $confirmed = true;
                } else if (str_contains($statusStr, "planned")) {
                    $status = false;
                    $confirmed = true;
                } else {
                    $status = false;
                    $confirmed = false;
                }
                // Controlla se il beacon esiste
                $selectStmt->execute();
                $b_result = $selectStmt->get_result();
                $b_count = mysqli_num_rows($b_result);

                if ($b_count > 0) {
                    // Il beacon esiste, aggiorno
                    HtmlPrint("Beacon: " . $callsign . " on " . $band . "MHz exists and will be updated. Result: " . $updateStmt->execute() . $updateStmt->get_result());
                    $telegramMessage .= "Upd: " . strval($callsign) . "\n";
                    $bcnUpdated += 1;
                } else {
                    // Aggiungo il beacon
                    HtmlPrint("Beacon: " . $callsign . " on " . $band . "MHz have to be added. Result: " . $insertStmt->execute() . $insertStmt->get_result());
                    $telegramMessage .= "Add: " . strval($callsign) . "\n";
                    $bcnAdded += 1;
                }
            }
        } else {
            // This lines does not contain a beacon
        }
    }

    // Stampa statistiche
    $bcnStats = "Added: " . $bcnAdded . ", Updated: " . $bcnUpdated . ", Skipped: " . $bcnSkipped;
    echo "<p>" . $bcnStats . "</p>";
    // Aggiunta statistiche al messaggio
    $telegramMessage .= "\n" . $bcnStats;
    // Invia risultato finale messaggio
    SendTelegramMessage($telegramMessage);
    ?>
    <table>
        <tr>
            <td>
                <a href="../index.php" class="button" style="padding: 0px;"><br>Back to admin</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="../../index.php" class="button" style="padding: 0px;"><br>Back to index</a>
            </td>
        </tr>
    </table>
</body>

</html>