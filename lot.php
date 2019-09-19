<?php

require_once('inc/functions.php');
require_once('inc/init.php');
require_once('inc/data.php');

// Проверяем существование параметра запроса с id лота.
if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
        ['error' => 'Ошибка 404: Страница не найдена']);
    print($content);
    die();
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($con, $sql, []);

$user = $_SESSION['user']['id'];

//CREATE TABLE lot (
//    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//  name VARCHAR(255) NOT NULL,
//  description TEXT NOT NULL,
//  img_path VARCHAR(255) NOT NULL,
//  start_price INT NOT NULL,
//  finish_date DATETIME NOT NULL,
//  bid_step INT NOT NULL,
//  user_id INT NOT NULL,
//  winner_id INT,
//  category_id INT NOT NULL,
//  is_active TINYINT NOT NULL,

//CREATE TABLE bid (
//  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//  price INT NOT NULL,
//  user_id INT NOT NULL,
//  lot_id INT NOT NULL,
//
//$sql_id = "SELECT l.name, l.img_path, l.start_price, l.bid_step, c.name c, l.finish_date, l.description, l.user_id, r.price rat, r.rate_user FROM lots l
//    LEFT JOIN category c
//    ON l.category_id = c.id
//    LEFT JOIN rate r
//    ON r.rate_lots = l.id
//    WHERE l.id = ?
//    ORDER BY r.date_create DESC";
//
//// Сформируйте и выполните SQL на чтение записи из таблицы с лотами, где id лота равен полученному из параметра запроса.
//$lot_id = db_fetch_data_assos($con, $sql_id, [$_GET['id']]);
//if (!$lot_id) {
//    http_response_code(404);
//    $content = include_template('404.php',
//        ['error' => '404 Страница не найдена']);
//    print($content);
//    die();
//}
//if ($lot_id["rat"]) {
//    $lot_id['price'] = $lot_id['rat'];
//}
//$lot_id['min'] = $lot_id['price'] + $lot_id['step'];
//$sql_rates = 'SELECT r.id, r.date_create, r.price, u.name FROM rate r
//        LEFT JOIN user u
//        ON u.id = r.rate_user
//        WHERE r.rate_lots = ?
//        ORDER BY r.date_create DESC';
//$result = db_fetch_data($con, $sql_rates, [$_GET['id']]);
//$count = count($result);
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $required = ['cost'];
//    $dict = ['cost' => 'Введите сумму'];
//    $errors = [];
//    if (empty($_POST['cost'])) {
//        $errors['cost'] = 'form__item--invalid';
//        $page_lot = include_template('lot.php', [
//            'lots_id' => $lot_id,
//            'category' => $category,
//            'errors' => $errors,
//            'dict' => $dict
//        ]);
//        print($page_lot);
//        die();
//    }
//    if (!ctype_digit($_POST['cost'])) {
//        $errors['cost'] = 'form__item--invalid';
//        $dict['cost'] = 'Неккоректная сумма';
//        $page_lot = include_template('lot.php', [
//            'lots_id' => $lot_id,
//            'category' => $category,
//            'errors' => $errors,
//            'dict' => $dict
//        ]);
//        print($page_lot);
//        die();
//    }
//    if ($lot_id['min'] >= $_POST['cost']) {
//        $errors['cost'] = 'form__item--invalid';
//        $dict['cost'] = 'Ставка должна быть больше минимальной цены';
//        $page_lot = include_template('lot.php', [
//            'lots_id' => $lot_id,
//            'category' => $category,
//            'errors' => $errors,
//            'dict' => $dict
//        ]);
//        print($page_lot);
//        die();
//    }
//    $sql_rate = "INSERT INTO rate (date_create, price, rate_lots, rate_user)
//                    VALUES (?, ?, ?, ?)";
//    $result_rate = db_insert_data($con, $sql_rate,
//        [date('Y.m.d H:i:s'), $_POST['cost'], $_GET['id'], $_SESSION['user']['id']]);
//    check($result_rate);
//    $lot_id['rate_user'] = $_SESSION['user']['id'];
//    $count = $count + 1;
//}
//


$navigation = include_template('main_nav.php', ['categories' => $categories]);

$page_content = include_template('lot_tpl.php', ['navigation' => $navigation]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Название лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'error' => $page_error
]);

print($layout_content);








