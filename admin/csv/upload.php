<?php
require_once '../../connect.php';
require_once '../../functions.php';
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    // Allow certain file formats
    if ($fileType != "csv") {
        WaitForPopup("Sorry, only CSV files are allowed.", "import.php");
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        WaitForPopup("Sorry, file already exists.", "./import.php");
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        WaitForPopup("Sorry, your file was not uploaded.", "./import.php");
        // if everything is ok, try to upload file
    } else {
        $leave_files = array('backup.csv', strval($_FILES["fileToUpload"]["tmp_name"]));

        foreach (glob("$target_dir*") as $file) {
            if (!in_array(basename($file), $leave_files)) {
                unlink($file);
            }
        }
        
        $target_file = $target_dir . date("Ymd-His") . "." . $fileType;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],  $target_file)) {
            SendTelegramMessage("File '" . htmlspecialchars($target_file) . "' was uploaded");
            WaitForPopup("The file " . htmlspecialchars($target_file) . " has been uploaded.", "import.php");
        } else {
            SendTelegramMessage("File '" . htmlspecialchars($target_file) . "' upload failed");
            WaitForPopup("Sorry, there was an error uploading your file.", "import.php");
        }
    }
}
