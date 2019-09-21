<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
        ['error' => 'Ошибка 404: Страница не найдена']);
    print($content);
    die();
}

// Сформируйте и выполните SQL на чтение записи из таблицы с лотами, где id лота равен полученному из параметра запроса
//$sql_lot_id = 'SELECT l.name, l.img_path, l.start_price, l.bid_step, c.name c, l.finish_date, l.description, l.user_id, b.price, b.user_id FROM lots l
//    LEFT JOIN category c
//    ON l.category_id = c.id
//    LEFT JOIN bid b
//    ON b.lot_id = l.id
//    WHERE l.id = ?
//    ORDER BY b.date DESC';

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
    'lot' => $lot,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'lot_title' => 'Тайтл лота'
]);

print($lot_template);









