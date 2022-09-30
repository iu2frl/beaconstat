<!DOCTYPE html>
<html>

<head>
    <title>Beaconstat</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
</head>

<body class="container">
    <?php

    require_once '../connect.php';
    require_once '../functions.php';

    if (!(isset($_GET['bid']))) {
        WaitForPopup("Invalid beacon_id received", "../index.php");
    }

    $b_id = $_GET['bid'];
    if (!is_numeric($b_id)) {
        WaitForPopup("Incorrect parameter received", "./index.php");
    }

    $stmt = $db->prepare("SELECT `status` FROM `bs_beacon` WHERE `id`=?");
    if ($stmt == FALSE) {
        WaitForPopup("Error preparing query", "./index.php");
    } else {
        $beaconId = intval($b_id);
        $stmt->bind_param('i', $beaconId);
        //var_dump($b_id);
        //var_dump($stmt);
        if ($stmt == FALSE) {
            WaitForPopup("Error binding query", "./index.php");
        } else {
            $stmt->execute();
        }
    }

    // controllo l'esito
    if (!$stmt) {
        // die("Errore nella query $query: " . mysqli_error());
        WaitForPopup("Error executing query", "./index.php");
    }

    $result = $stmt->get_result();
    //var_dump($result);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<p>Previous beacon status: ";
        if ($row["status"]) {
            echo "ON";
        } else {
            echo "OFF";
        }
        echo "</p>";

        $newState = !boolval($row["status"]);

        $stmt = $db->prepare("UPDATE `bs_beacon` SET `status`=? WHERE `bs_beacon`.`id`=?");
        if ($stmt == FALSE) {
            WaitForPopup("Error preparing QUERY", "../index.php");
        } else {
            $newVal = boolval($newState);
            $beaconId = intval($b_id);
            $stmt->bind_param('ii', $newVal, $beaconId);
            //var_dump($b_id);
            //var_dump($stmt);
            if ($stmt == FALSE) {
                WaitForPopup("Error binding QUERY", "../index.php");
            } else {
                $stmt->execute();
            }
        }

        // controllo l'esito
        if (!$stmt) {
            // die("Errore nella query $query: " . mysqli_error());
            WaitForPopup("Errore executing QUERY", "../index.php");
        } else {
            echo "<p>Current status: ";
            if ($newState) {
                echo "ON";
            } else {
                echo "OFF";
            }
            echo "</p>";
            SendTelegramMessage(
                "Beacon '" . $beaconId . "' TX status was changed.\n\n" .
                    "New status: " . intval($newState)
            );
        }
    } else {
        WaitForPopup("ERROR! Beacon not found", "../index.php");
    }
    ?>

    <a href="../index.php" class="button">Return to<br>beacons list</a>
    <a href="../viewreport.php?bid=<?php echo $b_id ?>" class="button">Return to<br>beacon status</a>
</body>

</html>