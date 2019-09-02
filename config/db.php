<?php

$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave'
];



//$con = mysqli_connect("localhost", "root", "", "yeticave");
//mysqli_set_charset($con, "utf8");
//
//if ($con == false) {
//    print(" Ошибка подключения: " . mysqli_connect_error());
//} else {
//    print(" Соединение установлено");
//    // выполнение запросов
//}
//
//require_once('../classes/data.php');

//foreach ($categories as $key => $val) {
//
//    $sql = "INSERT INTO category SET class = '$key', name = '$val' ";
//    $result = mysqli_query($con, $sql);
//
//};
//
//if (!$result) {
//    $error = mysqli_error($con);
//    print(" Ошибка MySQL: " . $error);
//}


//foreach ($lots as $val) {
//    $name = $val["name"];
//    $price = $val["price"];
//
//    $sql = "INSERT INTO lot SET name = '$name', description = 'Описание лота' , img_path = '/img/img.jpg' , start_price = $price , finish_date = '2019-09-26 00:00:00', user_id = 1 , category_id = 2 , bid_step = 100 , is_active = 1 ";
//    $result = mysqli_query($con, $sql);
//
//};
//
//
//if (!$result) {
//    $error = mysqli_error($con);
//    print(" Ошибка MySQL: " . $error);
//}


