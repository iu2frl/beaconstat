<!DOCTYPE html>
<html>

<head>
    <title>Beaconstat</title>
    <link rel="stylesheet" type="text/css" href="../../main.css">
</head>

<body class="container">
    <?php

    // Estrai nome dal database
    $b_id = $_GET['bid'];
    if (!is_numeric($b_id)) {
        WaitForPopup("Errore, parametro non corretto", "../index.php");
    }
    require_once '../../connect.php';
    require_once '../../functions.php';

    $stmt = $db->prepare("SELECT `callsign` FROM `bs_beacon` WHERE `id`=?");
    if ($stmt == FALSE) {
        WaitForPopup("Error preparing query", "../index.php");
    } else {
        $beaconId = intval($b_id);
        $stmt->bind_param('i', $beaconId);
        if ($stmt == FALSE) {
            WaitForPopup("Error binding query", "../index.php");
        } else {
            $stmt->execute();
        }
    }

    // controllo l'esito
    if (!$stmt) {
        // die("Errore nella query $query: " . mysqli_error());
        WaitForPopup("Error executing query", "../index.php");
    }

    $result = $stmt->get_result();
    //var_dump($result);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<p>Beacon: " . $row["callsign"] . "</p>";

        $stmt = $db->prepare("DELETE FROM `bs_beacon` WHERE `id`=?");
        if ($stmt == FALSE) {
            WaitForPopup("Error preparing query", "../index.php");
        } else {
            $beaconId = intval($b_id);
            $stmt->bind_param('i', $beaconId);
            if ($stmt == FALSE) {
                WaitForPopup("Error binding query", "../index.php");
            } else {
                $stmt->execute();
            }
        }

        // controllo l'esito
        if (!$stmt) {
            // die("Errore nella query $query: " . mysqli_error());
            WaitForPopup("Query returned error", "../index.php");
        } else {
            SendTelegramMessage("Beacon: '" . $row["callsign"] . " with id: " . $beaconId . "' was deleted");
            echo "<p>Beacon deleted ";
            echo "</p>";
        }
    } else {
       WaitForPopup("Error deleting beacon", "../index.php");
    }
    ?>

    <a href="./analyze_db.php" class="button">Return to<br>beacons analysis</a>
    <a href="../index.php" class="button">Return to<br>admin panel</a>
</body>

</html>