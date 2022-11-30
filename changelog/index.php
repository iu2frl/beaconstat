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
    <p>This file was automatically parsed from the README.md file using <a href="https://github.com/erusev/parsedown">erusev/parsedown</a> class</p>
</body>

</html>