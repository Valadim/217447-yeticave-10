<?php

require_once('classes/functions.php');
require_once('classes/data.php');
require_once('classes/init.php');

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




$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);

print(include_template('index.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]));


