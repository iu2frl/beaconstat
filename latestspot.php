<!DOCTYPE html>
<html lang="en">
<?php 
        include_once "./config/bs_config.php";
    ?>
<head>
    <title><?php echo $masterSiteName ?></title>
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>

<body class="container">
    <h1 style="text-align: center;">Latest 50 received reports</h1>
    <?php
    require_once './connect.php';

    $stmt = $db->prepare("SELECT `data`, `callsign`, `locator`, `status`, `antenna`, `beacon_id` FROM `bs_report` WHERE 1 ORDER BY `data` DESC LIMIT 50");
    if ($stmt == FALSE) {
        WaitForPopup("Error preparing query", "./index.php");
    } else {
        //$indexVar = 'i';
        //$q_band = '50';
        //$intValBand = intval($q_band);
        //$stmt->bind_param($indexVar, $intValBand);

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

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "<table style='border: 0px solid;'>
                <tr  class='intestazione'>
                    <th>Report date</th>
                    <th>RX Callsign</th>
                    <th>RX Locator</th>
                    <th>RX Antenna</th>
                    <th>Beacon</th>
                    <th>Band</th>
                    <th>QRG</th>
                    <th>Locatoe</th>
                    <th>Heard?</th>
                    <th>Cluster</th>
                    <th>Report</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            $beacon_id = $row['beacon_id'];

            $stmt = $db->prepare("SELECT `callsign`, `locator`, `qrg`, `band` FROM `bs_beacon` WHERE `id`=?");
            if ($stmt == FALSE) {
                WaitForPopup("Error preparing query", "./index.php");
            } else {
                $indexVar = 'i';
                $intValue = intval($beacon_id);
                $stmt->bind_param($indexVar, $intValue);

                if ($stmt == FALSE) {
                    WaitForPopup("Error binding query", "./index.php");
                } else {
                    $stmt->execute();
                }
            }

            $b_res = $stmt->get_result();
            $b_id = mysqli_fetch_assoc($b_res);

            echo "<tr><td>" .
                substr($row['data'], 0, 10) . "</td><td>" .
                strtoupper($row["callsign"]) . "</td><td>" .
                strtoupper($row["locator"]) . "</td><td>" .
                strtoupper($row["antenna"]) . " </td><td> " .
                $b_id["callsign"] . " </td><td> " .
                $b_id["band"] . " </td><td> " .
                $b_id["qrg"] . " </td><td> " .
                strtoupper($b_id["locator"]) . " </td> ";
            if ($row["status"]) {
                echo "<td style='text-align:center; color:green'><b>YES</b></td>";
            } else {
                echo "<td style='text-align:center; color:red'><b>NO</b></td>";
            }

            echo "<td><a href='http://www.dxsummit.fi/#/?dx_calls=" . $b_id["callsign"] . "' target='_blank'>DXSummit</a></td>";
            echo "<td><a href='./viewreport.php?bid=" . $beacon_id . "'>View</a></td>";
            echo "</tr>";
        }
        echo "</table> ";
    } else {
        echo "No report were returned";
    }
    ?>
    <table>
        <td>
            <a href="./index.php" class="button">Return to<br>beacons list</a>
        </td>
    </table>
</body>

</html>