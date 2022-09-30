<?php
require 'config/bs_config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = mysqli_connect($masterDbHost, $masterDbUserName, $masterDbPassword, $masterDbName);

if (!$db) {
    die('Can\'t connect: ' . mysqli_connect_error());
}

$db_selected = mysqli_select_db($db, $masterDbName);

if (!$db_selected) {
    die('Can\'t use DB : ' . mysqli_error($db));
}