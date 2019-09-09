<?php
$db = require_once('config/db.php');

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);



if (!$con) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {

    mysqli_set_charset($con, "utf8");
}
