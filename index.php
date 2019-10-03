<?php

require_once('inc/functions.php');
require_once('inc/init.php');
require_once('getwinner.php');



if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {

    $sql_category = 'SELECT `class`, `name` FROM category';
    $result_category = mysqli_query($con, $sql_category);

    $sql_lot = 'SELECT lot.id, lot.name, lot.start_price, lot.img_path, lot.finish_date, category.name AS category_name FROM lot '
        . 'JOIN category ON lot.category_id = category.id '
        . 'WHERE lot.finish_date > NOW() AND lot.winner_id IS NULL '
        . 'GROUP BY lot.id '
        . 'ORDER BY lot.date DESC LIMIT 9';
    $result_lot = mysqli_query($con, $sql_lot);

    if ($result_category && $result_lot) {
        $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);

        $page_content = include_template('main.php', [
            'categories' => $categories,
            'lots' => $lots
        ]);

    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}
$error = '';
$page_error = include_template('error.php', [
    'error' => $error
]);

$navigation = include_template('promo_nav.php', ['categories' => $categories]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'YetiCave - Главная страница',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'main_class' => 'container',
    'error' => $page_error,
    'navigation' => $navigation
]);

print($layout_content);




