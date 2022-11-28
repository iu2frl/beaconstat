<?php
require_once '../../functions.php';
?>

<html>

<head>
    <title>Beacons database importer</title>
    <link rel="stylesheet" type="text/css" href="../../main.css">
    <?php
    $mostRecentFile = GetMostRecentFile('uploads');
    ?>
</head>

<body>
    <h1>Beacon import sequence</h1>
    <h3>1. Select XLSX file to import</h3>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>
    <h3>2. Latest uploaded file is:</h3>
    <?php
    echo $mostRecentFile;
    ?>
    <h3>3. Start processing</h3>
    <?php
        if (str_contains($mostRecentFile, "xls")) {
            echo "<a href=\"./process.php\"><button>Process file " . $mostRecentFile . "</button></a>";
        } else {
            echo "No files to process";
        }
    ?>
    <br><br>

    <a href="../index.php" class="button" style="padding: 0px;"><br>Back</a>

</body>

</html>