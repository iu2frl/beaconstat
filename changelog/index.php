<html lang="en">

<head>
    <title>Beaconstat Changelog</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
</head>

<body>
    <?php
    require_once("./Parsedown.php");
    $file = file_get_contents('../CHANGELOG.md');
    $Parsedown = new Parsedown();

    echo $Parsedown->text($file);

    ?>
    <br><br>
    <h6>This file was automatically parsed from the README.md file using <a href="https://github.com/erusev/parsedown">erusev/parsedown</a> class</h6>

    <table>
        <tr>
            <td>
                <a href="../index.php" class="button"><br>Back</a>
            </td>
        </tr>
    </table>
</body>

</html>