<?php
require_once('inc/functions.php');
require_once('inc/init.php');

if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
        ['error' => 'Ошибка 404: Страница не найдена']);
    print($content);
    die();
}

$cur_id = $_GET['id'];

$sql_category = 'SELECT `class`, `name` FROM category';
$result_category = mysqli_query($con, $sql_category);

$sql_lot = "SELECT lot.id, lot.name, lot.description, lot.start_price, lot.bid_step, lot.img_path, lot.finish_date, category.name AS category_name FROM lot
    JOIN category ON lot.category_id = category.id
    WHERE lot.id = $cur_id";

$result_lot = mysqli_query($con, $sql_lot);

if ($result_category && $result_lot) {
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    $lot = mysqli_fetch_assoc($result_lot);
} else {
    $error = mysqli_error($con);
    $page_content = include_template('error.php', ['error' => $error]);
}

$navigation = include_template('main_nav.php', ['categories' => $categories]);

$lot_template = include_template('lot_tpl.php', [
    'navigation' => $navigation,
    'lot' => $lot
]);

$layout_content = include_template('layout.php', [
    'content' => $lot_template,
    'navigation' => $navigation,
    'title' => 'Тайтл лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);









