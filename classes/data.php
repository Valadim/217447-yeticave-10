<?php
require_once('classes/init.php');
$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя



if (!$con) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {
    $sql = 'SELECT `class`, `name` FROM category';
    $result = mysqli_query($con, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $content = include_template('error.php', ['error' => $error]);
    }
}

if (!$con) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {
    $sql = 'SELECT `id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `category_id`, `is_active` FROM lot';
    $result = mysqli_query($con, $sql);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        $content = include_template('error.php', ['error' => $error]);
    }
}




//$categories = [
//    "boards" => "Доски и лыжи",
//    "attachment" => "Крепления",
//    "boots" => "Ботинки",
//    "clothing" => "Одежда",
//    "tools" => "Инструменты",
//    "other" => "Разное"
//];

//$lots = [
//    [
//        "name" => "2014 Rossignol District Snowboard",
//        "category" => "Доски и лыжи",
//        "price" => 10999,
//        "image" => "img/lot-1.jpg"
//    ],
//    [
//        "name" => "DC Ply Mens 2016/2017 Snowboard",
//        "category" => "Доски и лыжи",
//        "price" => 159999,
//        "image" => "img/lot-2.jpg"
//    ],
//    [
//        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
//        "category" => "Крепления",
//        "price" => 8000,
//        "image" => "img/lot-3.jpg"
//    ],
//    [
//        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
//        "category" => "Ботинки",
//        "price" => 10999,
//        "image" => "img/lot-4.jpg"
//    ],
//    [
//        "name" => "Куртка для сноуборда DC Mutiny Charocal",
//        "category" => "Одежда",
//        "price" => 7500,
//        "image" => "img/lot-5.jpg"
//    ],
//    [
//        "name" => "Маска Oakley Canopy",
//        "category" => "Разное",
//        "price" => 5400,
//        "image" => "img/lot-6.jpg"
//    ]
//];
