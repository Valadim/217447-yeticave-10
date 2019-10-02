<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$db = require_once('config/db.php');

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$con) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    echo $content;
    die;
}

mysqli_set_charset($con, "utf8");

$is_auth = rand(0, 1);
$user_name = "Вадим";

$sql_category = 'SELECT `id`, `name` FROM category';
$categories = get_db_assoc($con, $sql_category);

