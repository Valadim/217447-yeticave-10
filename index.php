<?php

require_once('classes/functions.php');
require_once('classes/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим";

if (!$con) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {

    $sql_category = 'SELECT `class`, `name` FROM category';
    $result_category = mysqli_query($con, $sql_category);

    $sql_lot = 'SELECT lot.id, lot.name, lot.start_price, lot.img_path, lot.finish_date, category.name AS category_name FROM lot '
        . 'JOIN category ON lot.category_id = category.id '
        . 'WHERE lot.finish_date > NOW() AND lot.winner_id IS NULL '
        . 'GROUP BY lot.id '
        . 'ORDER BY lot.date DESC';
    $result_lot = mysqli_query($con, $sql_lot);

    if ($result_category && $result_lot) {
        $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);

    } else {
        $error = mysqli_error($con);
        $content = include_template('error.php', ['error' => $error]);
    }
}

$page_error = include_template('error.php', [
    'error' => $error
]);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'error' => $page_error
]);

print($layout_content);




