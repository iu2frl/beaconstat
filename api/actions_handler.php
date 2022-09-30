<!DOCTYPE html>
<html>

<head>
    <title>Beaconstat</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
</head>

<body class="container">

    <?php

    // Importa funzioni comuni
    require_once '../connect.php';

    // Importa lista chiavi API
    $myKeyChain = require './keys.php';

    // Stampa chiavi importate
    if ($debug) {
        echo "<p>Keys:</p>";
        var_dump($myKeyChain);
    }

    /* Formato della richiesta di ingresso
        actions_handler.php
            id: valore univoco dell'oggetto da modificare [Int32]
            action: azione da compiere (delete, update, insert) [string]
            target: tipo di oggetto di destinazione (beacon, report) [string]
            key: API KEY della richiesta [string]
    */

    // Controllo che tutti i valori siano validi
    if (isset($_GET['id']) || $_GET['id']) {
        if (isset($_GET['action']) || $_GET['action']) {
            if (isset($_GET['target']) || $_GET['target']) {
                if (isset($_GET['key']) || $_GET['key']) {
                    $targetId = intval($_GET["id"]);
                    $action = htmlspecialchars(strval($_GET["action"]));
                    $targetType = htmlspecialchars(strval($_GET["target"]));
                    $requestKey = htmlspecialchars(strval($_GET["key"]));
                    $result = true;
                    if ($debug) {
                        echo "<p>Data:</p>";
                        echo "Target: "; var_dump($targetId); echo "<br>";
                        echo "Action: "; var_dump($action); echo "<br>";
                        echo "Type: "; var_dump($targetType); echo "<br>";
                        echo "API: "; var_dump($requestKey);
                    }
                }
            }
        }
    } else {
        // Altrimenti ritorna alla pagina iniziale
        $result = false;
    }

    if ($result) {
        // Controllo validità API Key
        if (!in_array($requestKey, $myKeyChain, true)) {
            if ($debug) {
                echo 'Invalid API KEY';
            } else {
                $result = false;
            }
        }
    }
    if ($result) {
        // Controllo tipo di azione da fare
        switch ($targetType) {
            case 'beacon':
                // Richiesta di modifica beacon
                switch ($action) {
                    case 'delete':
                        // Richiesta di delete
                        $result = DeleteRow($targetId, 'bs_beacon');
                        break;
                    default:
                        // Comando non riconosciuto
                        if ($debug) {
                            echo 'Invalid action';
                        } else {
                            $result = false;
                        }
                        break;
                }
                break;
            case 'report':
                // Richiesta di modifica report
                switch ($action) {
                    case 'delete':
                        // Richiesta di delete
                        $result = DeleteRow($targetId, 'bs_beacon');
                        break;
                    default:
                        // Comando non riconosciuto
                        if ($debug) {
                            echo 'Invalid action', '../index.php';
                        } else {
                            $result = false;
                        }
                        break;
                }
            default:
                // Comando non riconosciuto
                if ($debug) {
                    echo 'Invalid target type', '../index.php';
                } else {
                    $result = false;
                }
                break;
        }
    }

    if ($result) {
        echo "<p>Success!</p>";
        SendTelegramMessage(
            "API Request processed\n\n" .
             "Action: " . $action .
             "\nTarget: " . $targetId .
             "\nType: " . $targetType .
             "\nFrom: " . $_SERVER['REMOTE_ADDR']
            );
    } else {
        echo "<p>Fail!</p>";
        SendTelegramMessage(
            "API Request rejected\n\n" .
             "Action: " . $action .
             "\nTarget: " . $targetId .
             "\nType: " . $targetType .
             "\nFrom: " . $_SERVER['REMOTE_ADDR']
            );
    }

    function DeleteRow(int $tId, string $tableName)
    {
        // Connessione al Database
        require_once "../connect.php";
        // Preparazione della query
        $stmt = $db->prepare("DELETE FROM $tableName WHERE `id`=?");
        if ($stmt == FALSE) {
            return false;
        } else {
            $stmt->bind_param('i', $tId);
            if ($stmt == FALSE) {
                return false;
            } else {
                // Esecuzione della query
                $stmt->execute();
            }
        }

        // controllo l'esito
        if (!$stmt) {
            return false;
        } else {
            return true;
        }
    }
    ?>
</body>

</html>