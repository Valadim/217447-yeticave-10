<?php
require_once 'functions.php';
$db = require_once '../config/db.php';

var_dump($db);
$con = mysqli_connect($db['host'], 'root', $db['password'], $db['database']);
mysqli_set_charset($con, "utf8");

if ($con == false) {
    print(" Ошибка подключения: " . mysqli_connect_error());
} else {
    print(" Соединение установлено");
}

$categories = [];
$content = '';
