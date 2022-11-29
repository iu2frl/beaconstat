<?php
// Mostra popup custom
if (!function_exists('GetArrayFromFile')) {
    function GetArrayFromFile($path)
    {
        $my_arr = require_once $path;
        return $my_arr;
    }
}

// Mostra popup custom
if (!function_exists('ShowPopup')) {
    function ShowPopup($text)
    {
        $popupCode = '<script language="javascript">alert("' . $text . '")</script>';
        return $popupCode;
    }
}

// Mostra popup e fai redirect
if (!function_exists('WaitForPopup')) {
    function WaitForPopup($text, $targetPage)
    {
        echo '<html><head>';
        echo '<meta http-equiv="refresh" content="0;' . $targetPage . '">';
        echo '</head><body>';
        echo ShowPopup($text);
        echo '</body></html>';
        exit();
    }
}

// Scrivi output su console
if (!function_exists('ConsolePrint')) {
    function ConsolePrint($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        echo "<script>console.log('Debug - " . $output . "' );</script>";
    }
}

// Scrivi CSS per dispositivi mobile
if (!function_exists('CssForMobile')) {
    function CssForMobile()
    {
        echo "<style>\n";
        echo "\t.collapse{\n\t\tdisplay: none;\n}";
        echo "</style>\n";
    }
}

// Mostra errore in HTML
if (!function_exists('HtmlPrint')) {
    function HtmlPrint($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        echo ("<p>" . $output . "</p>");
    }
}

// Mostra errore in HTML con die()
if (!function_exists('PrintAndDie')) {
    function PrintAndDie($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        die("Debug - " . $output);
    }
}

if (!function_exists('WriteFooter')) {
    function WriteFooter($footerTransl)
    {
        echo '<p>' . $footerTransl .  '&nbsp;<a href="https://www.iu2frl.it/contatti/">webmaster</a></p>';
    }
}

if (!function_exists('WriteMapHeader')) {
    function WriteMapHeader()
    {
        echo "<p style='text-align:center'><b>NOTE:</b>Beacon positioning is calculated based on 6-characters grid square locator, average precision is &plusmn;3Km</p>";
    }
}

if (!function_exists('WriteMapFooter')) {
    function WriteMapFooter()
    {
        echo "<p style='text-align:center'>Copyright 2022 by IU2FRL</p>";
    }
}

if (!function_exists('GetMostRecentFile')) {
    function GetMostRecentFile(string $relativePath, string $fileTypes)
    {
        $rgxTypes = "~\.(" . $fileTypes . ")$~";
        $files = preg_grep($rgxTypes, scandir($relativePath, SCANDIR_SORT_DESCENDING));
        if ($files) {
            return $files[0];
        } else {
            return "No valid files found";
        }
        
    }
}

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

// Disegna tabelle di index.php
if (!function_exists('DrawBeaconsTable')) {
    function DrawBeaconsTable($confirmed, $q_band, $translAry, $mobileDevice)
    {
        echo '<table style="';
        if (boolval($mobileDevice)) {
            echo " width='100%'";
        }
        echo '">';
        echo '<tr class=\'intestazione\'>';
        echo '<th style="text-align: left;">' . $translAry["trCallsign"] . '</th>';
        echo '<th colspan="2">' . $translAry["trQth"] . '</th>';
        echo '<!-- <th>Locatore</th> -->';
        echo '<th>' . $translAry["trQrg"] . '</th>';
        echo '<th>' . $translAry["trQah"] . '</th>';
        echo '<th class="collapse">' . $translAry["trAnt"] . '</th>';
        echo '<!-- <th>Direzione</th> -->';
        echo '<th class="collapse">' . $translAry["trMode"] . '</th>';
        echo '<th class="collapse">' . $translAry["trPower"] . '</th>';
        echo '<th>' . $translAry["trStatus"] . '</th>';
        echo '<!-- <th>Report</th> -->';
        echo '<th colspan="3">' . $translAry["trReports"] . '</th>';
        echo '</tr>';

        // Richiesti parametri database
        require 'connect.php';

        // Controllo validità campi
        if (is_numeric($q_band) && is_numeric($confirmed)) {
            // Pulizia campi ingresso
            $q_band = intval(htmlspecialchars($q_band));
            $confirmed = intval(boolval($confirmed));
        } else {
            // Campi non numerici
            PrintAndDie("Invalid input parameters");
        }

        // Preparazione query
        $stmt = $db->prepare("SELECT * FROM `bs_beacon` WHERE `band`=? AND `confirmed`=?");
        if ($stmt == FALSE) {
            PrintAndDie("Errore nella preparazione della QUERY");
        } else {
            // Esegui query
            $intval = intval($q_band);
            $confVal = intval($confirmed);
            $paramVal = 'ii';
            $stmt->bind_param($paramVal, $intval, $confVal);

            if ($stmt == FALSE) {
                PrintAndDie("Errore nel binding della QUERY");
            } else {
                $stmt->execute();
            }
        }

        // controllo l'esito
        if (!$stmt) {
            PrintAndDie("Errore nella esecuzione della QUERY");
        }

        $b_result = $stmt->get_result();

        if (!$b_result) {
            PrintAndDie("Il database ha riportato ERRORE");
        } else {

            if (mysqli_num_rows($b_result) > 0) {
                // Creo una riga per ogni beacon
                while ($row = mysqli_fetch_assoc($b_result)) {
                    echo "<tr><td style=\"text-align: left;\">" .
                        "<a href='./viewreport.php?bid=" . $row["id"] . "'>" . $row["callsign"] . "</a></td><td>" .
                        strtoupper($row["locator"]) . "</td><td>" .
                        ucwords($row["qth"]) . " </td><td style=\"text-align:center\"> " .
                        number_format($row["qrg"], 3, ',', ' ') . "</td><td style=\"text-align:center\">";
                    if ($row["asl"]>0) {
                        echo $row["asl"] . "m";
                    } else {
                        echo "";
                    }
                    echo "</td><td class='collapse'> " .
                        strtoupper($row["antenna"]) . " </td><td style=\"text-align:center\" class='collapse'> " .
                        strtoupper($row["mode"]) . " </td><td style=\"text-align:center\" class='collapse'> ";
                        if ($row["power"]>0.0) {
                            echo $row["power"] . "W";
                        } else {
                            echo "";
                        }
                    echo "</td>";
                    if ($row["status"]) {
                        echo "<td style='text-align:center; color:green'><b>" . $translAry["trActive"] . "</b>";
                    } else {
                        echo "<td style='text-align:center; color:red'><b>" . $translAry["trNotActive"] . "</b>";
                    }
                    echo "</td>";

                    $i_stmt = $db->prepare("SELECT `status`, `data`, `note`, `callsign` FROM `bs_report` WHERE `beacon_id`=? ORDER BY `data` DESC LIMIT 1");
                    if ($i_stmt == FALSE) {
                        PrintAndDie("Errore nella preparazione della QUERY");
                    } else {
                        $i_stmt->bind_param('i', $row["id"]);

                        if ($i_stmt == FALSE) {
                            PrintAndDie("Errore nel binding della QUERY");
                        } else {
                            $i_stmt->execute();
                        }
                    }

                    // controllo l'esito
                    if (!$i_stmt) {
                        PrintAndDie("Errore nella esecuzione della QUERY");
                    }
                    $i_result = $i_stmt->get_result();
                    if (!$i_result) {
                        PrintAndDie('Errore nella elaborazione dei risultati');
                    }
                    $i_dati = mysqli_fetch_assoc($i_result);

                    // Visualizzazione ultimo report e data
                    if (mysqli_num_rows($i_result) > 0) {
                        if ($i_dati['status']) {
                            echo "<td style='text-align:center; color:green'><b>" . $translAry["trHeard"] . "</b></td>";
                        } else {
                            echo "<td style='text-align:center; color:red'><b>" . $translAry["trNotHeard"] . "</b></td>";
                        }
                    } else {
                        echo "<td style='text-align:center; color:orange'><b>" . $translAry["trNoInfo"] . 
                        "</b></td>";
                    }
                    echo "<td> <a href='./sendreport.php?bid=" . $row["id"] . "'>" . $translAry["trReport"] . "</a></td>";
                    echo "<td> <a href='./map.php?loc=" . $row["locator"] . "&call=" . $row["callsign"] . "' target='_blank'>" . $translAry["trMap"] . "</a></td>";
                    echo "</tr> ";
                }
            } else {
                echo "<td colspan='14' class='label'>" . $translAry["trNoBeaconFound"] . "</td>";
            }
        }

        mysqli_close($db);
        echo '</table>';
    }
}

if (!function_exists('CheckIfTableExists')) {
    function CheckIfTableExists($tableName)
    {
        require 'connect.php';
        // Select 1 from table_name will return false if the table does not exist.
        $sh = $db->prepare("DESCRIBE `" . $tableName . "`");
        if ($sh->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('CreateBsBeaconTable')) {
    function CreateBsBeaconTable()
    {
        require 'connect.php';
        // Select 1 from table_name will return false if the table does not exist.
        $sh = $db->prepare("
            CREATE TABLE `bs_beacon` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `callsign` varchar(10) NOT NULL,
                `locator` varchar(6) NOT NULL,
                `qrg` double unsigned NOT NULL,
                `band` int(11) NOT NULL,
                `qth` varchar(20) NOT NULL DEFAULT 'n.d.',
                `asl` int(10) unsigned DEFAULT '0',
                `antenna` varchar(20) NOT NULL DEFAULT 'n.d.',
                `mode` varchar(10) NOT NULL DEFAULT 'n.d.',
                `qtf` varchar(10) NOT NULL DEFAULT 'n.d.',
                `power` float unsigned DEFAULT '0',
                `status` tinyint(1) NOT NULL DEFAULT '0',
                `confirmed` tinyint(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1 ;
        ");
        if ($sh->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('SendTelegramMessage')) {
    function SendTelegramMessage(string $content)
    {
        require 'config/bs_config.php';
        $data = [
            'chat_id' => $telegramChatId,
            'text' => $content
        ];
        $response = file_get_contents("https://api.telegram.org/bot" . $telegramBotToken . "/sendMessage?" .
            http_build_query($data));
    }
}

if (!function_exists('WriteHtmlHeader')) {
    function WriteHtmlHeader()
    {
        echo '<html>
                <head>
                    <title>Beaconstat</title>
                    <link rel="stylesheet" type="text/css" href="' . $_SERVER['HTTP_HOST'] . '/main.css">
                </head>
                <body>';
    }
}

if (!function_exists('WriteHtmlFooter')) {
    function WriteHtmlFooter()
    {
        echo '</body></html>';
    }
}

if (!function_exists('GetServerAddress')) {
    function GetServerAddress()
    {
        $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
        return $protocol . "://" . $_SERVER['SERVER_NAME'];
    }
}

