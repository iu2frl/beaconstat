<?php
require_once '../../functions.php';
?>

<html>

<head>
    <title>Beacons database importer</title>
    <link rel="stylesheet" type="text/css" href="../../main.css">
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
    echo GetMostRecentFile('uploads');
    ?>
    <h3>3. Start processing</h3>
    <a href="./process.php"><button>Process <?php echo GetMostRecentFile('uploads'); ?></button></a>
    <br><br>

    <a href="../index.php" class="button" style="padding: 0px;"><br>Back</a>

</body>

</html>