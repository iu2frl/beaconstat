<!DOCTYPE html>
<?php

require_once './functions.php';
require_once './config/bs_config.php';

// Cerca la banda di cui fare la query
$q_band =  isset($_POST['bandsw']) ? $_POST['bandsw'] : '';
if ($q_band == '') {
    // di default mostro i 2m
    $q_band = '144';
}

// Scelta della lingua da visualizzare
$lang =  isset($_POST['lang']) ? $_POST['lang'] : '';
// Pulizia parametro lingua
$lang = substr(strtolower(htmlspecialchars($lang)), 0, 2);
// provo a mostrare la lingua del browser
$brLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
// Se la lingua non arriva via POST prendo quella del browser
if ($lang == '') {
    $lang = $brLang;
}

// Controllo che il file delle traduzioni esista
if (!file_exists('./languages/' . strtolower($brLang) . '.php')) {
    // Fallback a inglese
    $lang = 'en';
}

// Get translations from file
$translAry = GetArrayFromFile('./languages/' . strtolower($lang) . '.php');
if (sizeof($translAry) <= 1) {
    die("Cant read translations for " . $lang);
}
?>

<html lang="<?php echo $lang; ?>">

<head>
    <title><?php echo $masterSiteName ?></title>
    <link rel="stylesheet" type="text/css" href="./main.css">
    <?php

    if ($enableMobile) {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            CssForMobile();
            $mobileDevice = true;
        } else {
            $mobileDevice = false;
        }
    } else {
        $mobileDevice = false;
    }


    if ($debug) {
        ConsolePrint('Band selection: ' . $q_band);
        ConsolePrint('Browser language: ' . $brLang);
        ConsolePrint('Page language: ' . $lang);
    }
    ?>
</head>

<body class="container">
    <h1 style="text-align:center"><?php echo $masterSiteName ?></h1>
    <p style="text-align:center"><?php echo $translAry["trDesc"] ?></p>

    <div style='text-align:center;'>
        <form action="./index.php" method="post" name="filterSwitch">
            <label for="lang" class='label'><?php echo $translAry["trLangSel"] ?>&nbsp;</label>
            <select id="lang" name="lang" onchange="filterSwitch.submit()">
                <?php
                // Warning, check security of this process!
                $files = glob('languages/*.{php}', GLOB_BRACE);
                foreach ($files as $file) {
                    // Extract file path
                    $filePathInfo = pathinfo($file);
                    // Extract file name without path and extension
                    $fileName = $filePathInfo['filename'];
                    if (strlen($fileName) == 2) {
                        // If name has 2 characters then use it
                        echo "<option value=" . $fileName;
                        if ($lang == $fileName) echo " selected";
                        echo ">" . strtoupper($fileName) . "</option>";
                    }
                }
                ?>
            </select>
            <br><br>
            <label for="bandsw" class='label'><?php echo $translAry["trBandSel"] ?>&nbsp;</label>
            <select id="bandsw" name="bandsw" onchange="filterSwitch.submit()">
                <option value="50" <?php if ($q_band == '50') echo "SELECTED"; ?>>6m</option>
                <option value="144" <?php if ($q_band == '144') echo "SELECTED"; ?>>2m</option>
                <option value="432" <?php if ($q_band == '432') echo "SELECTED"; ?>>70cm</option>
                <option value="1296" <?php if ($q_band == '1296') echo "SELECTED"; ?>>23cm</option>
                <option value="2320" <?php if ($q_band == '2320') echo "SELECTED"; ?>>13cm</option>
                <option value="5760" <?php if ($q_band == '5760') echo "SELECTED"; ?>>6cm</option>
                <option value="10368" <?php if ($q_band == '10368') echo "SELECTED"; ?>>3cm</option>
                <option value="24048" <?php if ($q_band == '24048') echo "SELECTED"; ?>>1.5cm</option>
                <option value="47088" <?php if ($q_band == '47088') echo "SELECTED"; ?>>7mm</option>
                <option value="76032" <?php if ($q_band == '76032') echo "SELECTED"; ?>>4mm</option>
            </select>
        </form>
        <br>
        <p><?php echo $translAry["trConfBeacons"] ?></p>
        <?php
        DrawBeaconsTable(1, $q_band, $translAry, $mobileDevice);
        ?>

        <p><?php echo $translAry["trUncBeacons"] ?></p>
        <?php
        DrawBeaconsTable(0, $q_band, $translAry, $mobileDevice);
        ?>
        <br><br>
        <table style="margin: 0 auto 0 auto;">
            <tr>
                <td style="text-align:center">
                    <a href="./addbeacon.php" class="button"><?php echo $translAry["trAddNewBeacon"] ?></a>
                </td>
                <td>
                    <a href="./latestspot.php" class="button"><?php echo $translAry["trLatestSpots"] ?></a>
                </td>
                <td>
                    <a href="./mapfull.php" class="button"><?php echo $translAry["trBeaconMap"] ?></a>
                </td>
            </tr>
        </table>
        <br>
        <?php
            echo '<p>' . $translAry["trFooter"] . '</p>';
            echo '<h6>
                    This project has been publicly released on <a href="https://github.com/iu2frl/beaconstat">iu2frl/beaconstat</a> under the <a href="./LICENSE">GPL-3.0 license</a><br>
                    Please report any issue to the <a href="https://www.iu2frl.it/contatti/">webmaster</a> or on <a href="https://github.com/iu2frl/beaconstat">iu2frl/beaconstat</a><br>
                    Changelog can be found at <a href="./changelog/">this link</a></h6>';
        ?>
    </div>
</body>

</html>