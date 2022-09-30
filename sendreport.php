<!DOCTYPE html>
<html lang="en">
<?php
include_once "./config/bs_config.php";
?>

<head>
	<title><?php echo $masterSiteName ?></title>
	<link rel="stylesheet" type="text/css" href="./main.css">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://www.google.com/recaptcha/api.js?render=6Ld6Bd8hAAAAAAAnvMpwVT0eWFLHgcfgCha4usQa"></script>
</head>

<body>
	<?php
	require_once './connect.php';
	$beacon_id = $_GET["bid"];
	if ($beacon_id == '') {
		WaitForPopup('Beacon id not valid', './index.php');
	}

	$stmt = $db->prepare("SELECT `callsign` FROM `bs_beacon` WHERE `id`=?");

	if ($stmt == FALSE) {
		WaitForPopup('Errore nella preparazione della QUERY', './index.php');
	} else {
		$indexVar = 'i';
		$intValue = intval($beacon_id);
		$stmt->bind_param($indexVar, $intValue);
		if ($stmt == FALSE) {
			WaitForPopup("Errore nel binding della QUERY", './index.php');
		} else {
			$stmt->execute();
		}
	}

	// controllo l'esito
	if (!$stmt) {
		WaitForPopup("Errore nella esecuzione della QUERY\n", './index.php');
	}

	$row = $stmt->get_result();
	if (mysqli_num_rows($row) > 0) {
		$row = mysqli_fetch_assoc($row);
		echo "<h1>Invia rapporto di ascolto per " . $row['callsign'] . "</h1>";
	} else {
		WaitForPopup('Nessun risultato per ' . $beacon_id, './index.php');
	}
	?>
	<table style="text-align: left;">
		<form action="./sendreport_handler.php" method="post" id="beaconReport">
			<input type="number" step="1" id="beacon_id" name="beacon_id" value="<?php echo $beacon_id ?>" hidden>
			<tr>
				<td><label for="callsgin">Operatore RX:</label></td>
				<td><input type="text" id="callsign" name="callsign" placeholder="IA2CCC" maxlength="10"></td>
			</tr>
			<tr>
				<td><label for="locator">Locatore RX:</label></td>
				<td><input type="text" id="locator" name="locator" placeholder="JN55je" maxlength="6"></td>
			</tr>
			<tr>
				<td><label for="data">Data di RX:</label></td>
				<td><input type="date" id="data" name="data"></td>
			</tr>
			<tr>
				<td><label for="antenna">Antenna in RX:</label></td>
				<td><input type="text" id="antenna" name="antenna" placeholder="Diamond X510" maxlength="15"></td>
			</tr>
			<tr>
				<td><label for="note">Note aggiuntive:</label></td>
				<td><input type="text" id="note" name="note" placeholder="Informazioni aggiuntive, es: segnale basso, QRM, fading, ecc..." maxlength="50"></td>
			</tr>
			<tr>
				<td><label for="heard">Hai sentito il beacon?</label></td>
				<td>
					<input type="checkbox" id="heard" name="heard" value="Si, ascoltato">
					<label for="heard">Beacon ascoltato</label>
				</td>
			</tr>
	</table>
	<table>
		<tr>
			<td><input class="button" type="submit" value="Invia report"></td>
		</tr>
		<tr>
			<td>
				<a href="./index.php" class="button" style="padding: 0px;"><br>Back</a>
			</td>
		</tr>
		</form>
	</table>

	<?php
	require_once './config/bs_config.php';
	echo "<script>
        $('#beaconReport').submit(function(event) {
            event.preventDefault();
            var beacon_id = $('#beacon_id').val();
            var callsign = $('#callsign').val();
            var locator = $('#locator').val();
            var data = $('#data').val();
            var antenna = $('#antenna').val();
            var note = $('#note').val();
            var heard = $('#heard').val();

            grecaptcha.ready(function() {
                grecaptcha.execute(\"" . $captchaPublicKey . "\", {
                    action: 'send_report'
                }).then(function(token) {
                    $('#beaconReport').prepend('<input type=\"hidden\" name=\"token\" value=\"' + token + '\">');
                    $('#beaconReport').prepend('<input type=\"hidden\" name=\"action\" value=\"send_report\">');
                    $('#beaconReport').unbind('submit').submit();
                });;
            });
        });
    </script>"
	?>
</body>

</html>