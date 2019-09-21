<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

//Создайте новый сценарий для показа страницы лота — lot.php.-----------
//Создайте новый шаблон, который будет подключаться в lot.php. Верстку для сценария взять из pages/lot.html.----------
//Добавьте карточкам обьявлений ссылки на сценарий lot.php вместе с параметром запроса.-----------
//Проверяйте существование параметра запроса с id лота.------------------
//Сформируйте и выполните SQL на чтение записи из таблицы с лотами, где id лота равен полученному из параметра запроса. ------------------

//Покажите информацию о лоте на странице.
//Не забудьте выделять оставшееся до истечения лота время красным цветом. Добавляйте блоку div.lot__timer класс timer--finishing, если осталось меньше часа.
//Если параметр запроса отсутствует, либо если по этому id не нашли ни одной записи, то вместо содержимого страницы возвращать код ответа 404.


// Проверяем существование параметра запроса с id лота.
if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
        ['error' => 'Ошибка 404: Страница не найдена']);
    print($content);
    die();
}

print( "введенный id: " . $_GET[ 'id' ]);

// Сформируйте и выполните SQL на чтение записи из таблицы с лотами, где id лота равен полученному из параметра запроса
//$sql_lot_id = 'SELECT l.name, l.img_path, l.start_price, l.bid_step, c.name c, l.finish_date, l.description, l.user_id, b.price, b.user_id FROM lots l
//    LEFT JOIN category c
//    ON l.category_id = c.id
//    LEFT JOIN bid b
//    ON b.lot_id = l.id
//    WHERE l.id = ?
//    ORDER BY b.date DESC';


//if (!$con) {
//    $error = mysqli_connect_error();
//    $page_content = include_template('error.php', ['error' => $error]);
//} else {
//
//    $sql_lot_id = 'SELECT l.name, l.img_path, l.start_price, l.bid_step, c.name c, l.finish_date, l.description, l.user_id, b.price, b.user_id, b.date FROM lots l '
//      .  'LEFT JOIN category c '
//      .  'ON l.category_id = c.id '
//      .  'LEFT JOIN bid b '
//      .  'ON b.lot_id = l.id '
//      .  'WHERE l.id = ? '
//      .  'ORDER BY b.date DESC ';
//    $result_lot_id = mysqli_query($con, $sql_lot_id);
//
//    if ($result_lot_id) {
//        $lot_id = mysqli_fetch_all($result_lot_id, MYSQLI_ASSOC);
//
//        $page_content = include_template('main.php', [
//            'categories' => $categories,
//            'lots' => $lots
//        ]);
//    } else {
//        $error = mysqli_error($con);
//        $page_content = include_template('error.php', ['error' => $error]);
//    }
//}
//
//print_r($lot_id);

$cur_id = $_GET[ 'id' ];

if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {

    $sql_category = 'SELECT `class`, `name` FROM category';
    $result_category = mysqli_query($con, $sql_category);

    $sql_lot = "SELECT lot.id, lot.name, lot.start_price, lot.img_path, lot.finish_date, category.name AS category_name FROM lot
    JOIN category ON lot.category_id = category.id
    WHERE lot.id = $cur_id" ;
    $result_lot = mysqli_query($con, $sql_lot);

    if ($result_category && $result_lot) {
        $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);


    } else {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
    }
}

print_r($lots);

//Покажите информацию о лоте на странице.

$navigation = include_template('main_nav.php', ['categories' => $categories]);

$lot_template = include_template('lot_tpl.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'lots' => $lots,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'lot_title' => 'Тайтл лота'
]);

print($lot_template);









