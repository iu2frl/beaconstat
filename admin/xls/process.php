<html>

<head>
<title>XLSX Import</title>
<link rel="stylesheet" type="text/css" href="../../main.css">
</head>

<body>
<?php
require_once '../../connect.php';
require_once '../../functions.php';

// Ottieni il file più recente
$xlsxFile = "uploads/" . GetMostRecentFile('uploads', "xls|xlsx");

// Controlla se la tabella del DB esiste
if (!CheckIfTableExists("bs_beacon")) {
    // Crea la tabella
    if (!CreateBsBeaconTable()) {
        WaitForPopup("Unable to create BS_BEACON table", "import.php");
    }
}

// Importa libreria XLSX
require './simplexlsx.class.php';
// Apri file più recente
$xlsx = new SimpleXLSX($xlsxFile);
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

// Parsing del file XLSX
$sheetNames = $xlsx->sheetNames();
// Controllo se ho almeno un foglio
if ($sheetNames) {
    // Preparazione messaggio Telegram
    $telegramMessage = "";
    // Preparazione contatori statistiche
    $bcnAdded = 0;
    $bcnUpdated = 0;
    $bcnSkipped = 0;
    
    // Ciclo per ogni foglio
    foreach ($sheetNames as $index => $name) {
        // Log per info
        HtmlPrint("Parsing sheet '" . $name . "' index: " . $index);
        // Estraggo righe dal foglio
        foreach ($xlsx->rows($index) as $r => $fields) {
            if ($fields[1] == 'CALL' || $fields[1] == '') {
                //row is skipped
            } else {
                //HtmlPrint($fields);
                // Leggi campi dal file XLSX e assegnali alle variabili locali
                $qrg = doubleval(str_replace("'", "", strval($fields[0]))) / (float)1000.0;
                $callsign = substr(strtoupper(strval($fields[1])), 0, 10);
                if ($qrg<(double)50.0) {
                    // Controllo che la frequenza sia almeno dei 6m
                    HtmlPrint("Beacon: " . $callsign . " has frequency below 50MHz (" . htmlspecialchars($qrg) . ") and will be skipped");
                    $bcnSkipped += 1;
                } else {
                    $band = floor($qrg);
                    $qth = substr(strval($fields[2]), 0, 20);
                    $locator = substr(strtoupper(strval($fields[3])), 0, 6);
                    $asl = intval($fields[4]);
                    $antenna = substr(strval($fields[5]), 0, 20);
                    $mode = substr(strtoupper(strval($fields[6])), 0, 10);
                    $qtf = substr(strval($fields[7]), 0, 10);
                    $power = (float)$fields[8];
                    $statusStr = strtolower(strval($fields[9])); // aggiungere controlli qui
                    // Controllo dello stato del beacon
                    if (str_contains($statusStr, "on")) {
                        $status = true;
                        $confirmed = true;
                    } else if (str_contains($statusStr, "test")) {
                        $status = true;
                        $confirmed = false;
                    } else if (str_contains($statusStr, "off")) {
                        $status = false;
                        $confirmed = true;
                    } else if (str_contains($statusStr, "plan")) {
                        $status = false;
                        $confirmed = true;
                    } else {
                        $status = false;
                        $confirmed = false;
                    }
                    
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
            }
        }
    }
    // Stampa statistiche
    $bcnStats = "Added: " . $bcnAdded . ", Updated: " . $bcnUpdated . ", Skipped: " . $bcnSkipped;
    echo "<p>" . $bcnStats . "</p>";
    // Aggiunta statistiche al messaggio
    $telegramMessage .= "\n" . $bcnStats;
    // Invia risultato finale messaggio
    SendTelegramMessage($telegramMessage);
} else {
    // Errore nella lettura del file
    WaitForPopup("File " . $xlsxFile . " is empty or not valid", "import.php");
}
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