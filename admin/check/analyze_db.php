<html>

<head>
    <title>Clean empty rows from DB</title>
    <link rel="stylesheet" type="text/css" href="../../main.css">
</head>

<body>
    <h1>Check for empty parameters</h1>
    <?php
    require_once '../../connect.php';
    require_once '../../functions.php';
    // Array campi tabella SQL
    $paramAry = array("callsign", "locator", "qrg", "band", "qth", "asl", "antenna", "mode", "qtf", "power", "status", "confirmed");
    for ($i = (int)0; $i < sizeof($paramAry); ++$i) {
        // Preparazione query select
        $queryStr = "SELECT `id`, `callsign` FROM `bs_beacon` WHERE LENGTH(" . strval($paramAry[$i]) . ") < 1";
        $selectStmt = $db->prepare($queryStr);
        // Mostro su quale riga sto lavorando
        echo "<h3>" . $i . ". Searching for empty " . strtoupper($paramAry[$i]) . " field</h3>";
        // Esecuzione query
        $selectStmt->execute();
        // Lettura risultati
        $selectStmtResult = $selectStmt->get_result();
        // Conteggio risultati
        $resultCnt = mysqli_num_rows($selectStmtResult);
        if ($resultCnt > 0) {
            // Creo una riga per ogni risultato
            while ($row = mysqli_fetch_assoc($selectStmtResult)) {
                echo "<p>Index <a href='../viewreport.php?bid=" .
                    $row["id"] . "'>" . $row["id"];
                if ($row["callsign"] != "") {
                    echo " - " . $row["callsign"];
                }
                echo "</a> has an empty " . strtoupper($paramAry[$i]) . " value, ".
                "Delete it? <a href='delete.php?bid=" . $row["id"] . "'>yes</a>".
                "</p>";
            }
        } else {
            echo "<p>No empty values found</p>";
        }
    }
    ?>
    <h1>Check for duplicates</h1>
    <?php
    // Array campi tabella SQL
    $paramAry = array("callsign", "locator", "qrg");
    for ($i = (int)0; $i < sizeof($paramAry); ++$i) {
        // Preparazione query select
        $queryStr = "SELECT `id`, `callsign`, `" . strval($paramAry[$i]) . "`, COUNT(" . strval($paramAry[$i]) . ") FROM `bs_beacon` GROUP BY " . strval($paramAry[$i]) . " HAVING COUNT(" . strval($paramAry[$i]) . ") > 1;";
        $selectStmt = $db->prepare($queryStr);
        // Mostro su quale riga sto lavorando
        echo "<h3>" . $i . ". Searching for duplicates in " . strtoupper($paramAry[$i]) . " field</h3>";
        // Esecuzione query
        $selectStmt->execute();
        // Lettura risultati
        $selectStmtResult = $selectStmt->get_result();
        // Conteggio risultati
        $resultCnt = mysqli_num_rows($selectStmtResult);
        if ($resultCnt > 0) {
            // Creo una riga per ogni risultato
            while ($row = mysqli_fetch_assoc($selectStmtResult)) {
                echo "<p>Index <a href='../viewreport.php?bid=" .
                    $row["id"] . "'>" . $row["id"];
                if ($row["callsign"] != "") {
                    echo " - " . $row["callsign"];
                }
                echo "</a> has a duplicated " . strtoupper($paramAry[$i]) . " value</p>";
            }
        } else {
            echo "<p>No empty values found</p>";
        }
    }
    ?>
    <br><br>
    <table>
        <tr>
            <td>
                <a href="../index.php" class="button" style="padding: 0px;"><br>Back</a>
            </td>
        </tr>
    </table>
</body>

</html>