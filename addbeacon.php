<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        include_once "./config/bs_config.php";
    ?>
    <title><?php echo $masterSiteName ?></title>
    <link rel="stylesheet" type="text/css" href="./main.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ld6Bd8hAAAAAAAnvMpwVT0eWFLHgcfgCha4usQa"></script>
</head>

<body>
    <h1>Add a new beacon</h1>
    <table style="text-align: left;">
        <form action="./addbeacon_handler.php" method="post" id="beaconData">
            <tr>
                <td><label for="callsgin">Callsign:</label></td>
                <td><input type="text" id="callsign" name="callsign" placeholder="IA2CCC/B" maxlength="10"></td>
            </tr>
            <tr>
                <td><label for="locator">Locator:</label></td>
                <td><input type="text" id="locator" name="locator" placeholder="JN55je" maxlength="6"></td>
            </tr>
            <tr>
                <td><label for="qrg">Qrg (Mhz):</label></td>
                <td><input type="number" id="qrg" name="qrg" step="0.000001" placeholder="144.414000" maxlength="11" max="300000"></td>
            </tr>
            <tr>
                <td><label for="QTH">QTH:</label></td>
                <td><input type="text" id="qth" name="qth" placeholder="Mantova MN" maxlength="15"></td>
            </tr>
            <tr>
                <td><label for="asl">ASL (m):</label></td>
                <td><input type="number" id="asl" name="asl" placeholder="1799" maxlength="10" min="0" max="10000"></td>
            </tr>
            <tr>
                <td><label for="mode">Mode:</label></td>
                <td><input type="text" id="mode" name="mode" placeholder="A1A" maxlength="10"></td>
            </tr>
            <tr>
                <td><label for="antenna">Antenna:</label></td>
                <td><input type="text" id="antenna" name="antenna" placeholder="Halo" maxlength="15"></td>
            </tr>
            <tr>
                <td><label for="qtf">QTF:</label></td>
                <td><input type="text" id="qtf" name="qtf" placeholder="Omni" maxlength="10"></td>
            </tr>
            <tr>
                <td><label for="power">Power (W):</label></td>
                <td><input type="number" id="power" name="power" placeholder="0.125" step="0.001" max="500" maxlength="3"></td>
            </tr>
            <tr>
                <td><label for="status">Status:</label></td>
                <td><input type="checkbox" id="status" name="status" value=true><label for="status">Beacon is active</label></td>
            </tr>
    </table>
    <br>
    <table>
        <tr>
            <td>
                <input type="submit" class="button" value="Add beacon">
            </td>
        </tr>
        <tr>
            <td>
                <a href="./index.php" class="button" style="padding: 0px;"><br>Back</a>
            </td>
        </tr>
        </form>
    </table>


    <script>
        $('#beaconData').submit(function(event) {
            event.preventDefault();
            var callsign = $('#callsign').val();
            var locator = $('#locator').val();
            var qrg = $('#qrg').val();
            var qth = $('#qth').val();
            var asl = $('#asl').val();
            var mode = $('#mode').val();
            var antenna = $('#antenna').val();
            var qtf = $('#qtf').val();
            var power = $('#power').val();
            var status = $('#status').val();

            grecaptcha.ready(function() {
                grecaptcha.execute('6Ld6Bd8hAAAAAAAnvMpwVT0eWFLHgcfgCha4usQa', {
                    action: 'send_beacon'
                }).then(function(token) {
                    $('#beaconData').prepend('<input type="hidden" name="token" value="' + token + '">');
                    $('#beaconData').prepend('<input type="hidden" name="action" value="send_beacon">');
                    $('#beaconData').unbind('submit').submit();
                });;
            });
        });
    </script>
</body>

</html>