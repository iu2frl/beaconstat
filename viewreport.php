<!DOCTYPE html>
<html lang="it">
<?php
require_once "./config/bs_config.php";
?>

<head>
    <title><?php echo $masterSiteName ?></title>
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>

<body class="container">
    <?php

    $b_id = $_GET['bid'];
    if (!is_numeric($b_id)) {
        WaitForPopup("Incorrect parameter received", "./index.php");
    }
    require_once './connect.php';

    $stmt = $db->prepare("SELECT * FROM `bs_beacon` WHERE `id`=?");
    if ($stmt == FALSE) {
        WaitForPopup("Error preparing query", "./index.php");
    } else {
        $indexVar = 'i';
        $intValue = intval($b_id);
        $stmt->bind_param($indexVar, $intValue);
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
    } else {
        WaitForPopup("Received empty response", "./index.php");
    }

    $b_callsign = $row["callsign"];
    echo "<h1 style=\"text-align: center;\">Beacon page for: " . $b_callsign . "</h1>"
    ?>

    <h2 style="text-align: center;">Beacon details</h2>

    <table>
        <thead>
            <tr class="intestazione">
                <th>Callsign</th>
                <th colspan="2">QTH</th>
                <!-- <th>Locatore</th> -->
                <th>QRG (MHz)</th>
                <th>QAH (slm)</th>
                <th>Antenna</th>
                <th>QTF</th>
                <th>Mode</th>
                <th>Power</th>
                <th colspan="2">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo "<tr><td>" .
                $row["callsign"] . "</td><td>" .
                strtoupper($row["locator"]) . "</td><td>" .
                $row["qth"] . " </td><td style='text-align:center;'> " .
                number_format($row["qrg"], 6, ',', ' ') . "</td><td style='text-align:center;'>" .
                $row["asl"] . "m </td><td> " .
                strtoupper($row["antenna"]) . " </td><td> " .
                strtoupper($row["qtf"]) . " </td><td> " .
                strtoupper($row["mode"]) . " </td><td style='text-align:center;'> " .
                $row["power"] . "W </td>";
            $beaconStatus = boolval($row["status"]);
            if ($beaconStatus) {
                echo "<td style='text-align:center; color:green'><b>ON</b></td>";
            } else {
                echo "<td style='text-align:center; color:red'><b>OFF<b></td>";
            }
            //echo ", ";
            $beaconConfirmed = boolval($row["confirmed"]);
            if ($beaconConfirmed) {
                echo "<td style='text-align:center; color:green'><b>Confirmed</b></td>";
            } else {
                echo "<td style='text-align:center; color:red'><b>Not confirmed<b></td>";
            }
            echo "</tr>";
            ?>
        </tbody>
    </table>
    <h2 style="text-align: center;">Latest beacon reports</h2>

    <?php

    $stmt = $db->prepare("SELECT `data`, `callsign`, `locator`, `status`, `antenna`, `note` FROM `bs_report` WHERE `beacon_id`=? ORDER BY `data` DESC LIMIT 20");

    if ($stmt == FALSE) {
        WaitForPopup("Error preparing query", "./index.php");
    } else {
        $indexVar = 'i';
        $intValue = intval($b_id);
        $stmt->bind_param($indexVar, $intValue);
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

    echo "<table>
                <tr  class='intestazione'>
                    <th>Report Date</th>
                    <th>Callsign</th>
                    <th>QTH</th>
                    <th>Antenna</th>
                    <th>Heard</th>
                    <th>Note</th>
                </tr>";

    if (mysqli_num_rows($result) > 0) {
        // output data of each row

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" .
                substr($row['data'], 0, 10) . "</td><td>" .
                strtoupper($row["callsign"]) . "</td><td>" .
                strtoupper($row["locator"]) . "</td><td>" .
                strtoupper($row["antenna"]) . " </td>";
            if ($row["status"]) {
                echo "<td style='text-align:center; color:green'><b>Yes</b></td>";
            } else {
                echo "<td style='text-align:center; color:red'><b>No</b></td>";
            }
            echo "<td>" . $row["note"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No reports found in the database</td></tr>";
    }
    echo "</table> ";

    ?>
    <h2 style="text-align: center;">Beacon actions</h2>
    <table>
        <tr>
            <td style='text-align:center;'>
                <a href="http://www.dxsummit.fi/#/?dx_calls=<?php echo $b_callsign ?>" target="_blank" class="button">Open <?php echo strtoupper($b_callsign); ?><br>on DXSUMMIT</a>
            </td>
            <td>
                <a href="./sendreport.php?bid=<?php echo $b_id ?>" class="button">Send report for<br><?php echo strtoupper($b_callsign); ?></a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="./admin/toggle_status.php?bid=<?php echo $b_id ?>" class="button">Set beacon to<br>
                    <?php if ($beaconStatus) {
                        echo "OFF";
                    } else {
                        echo "ON";
                    } ?>
                </a>
            </td>
            <td>
                <a href="./admin/toggle_confirm.php?bid=<?php echo $b_id ?>" class="button">Set <?php echo strtoupper($b_callsign); ?> to <br>
                    <?php if ($beaconConfirmed) {
                        echo "Not confirmed";
                    } else {
                        echo "Confirmed";
                    } ?>
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="./index.php" class="button">Back to<br>Index</a>
            </td>
            <td>

            </td>
        </tr>
    </table>
</body>

</html>