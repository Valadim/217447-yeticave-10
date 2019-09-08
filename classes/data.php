<?php
require_once('classes/init.php');
//require_once('classes/functions.php');
$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

if (!$con) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {
    $sql = 'SELECT `class`, `name` FROM category';
    $result = mysqli_query($con, $sql);

    $sql_lot_join = 'SELECT c.name FROM category c JOIN lot l ON c.name = l.category_id';
    $result_lot_join = mysqli_query($con, $sql_lot_join);

    $sql_lot = 'SELECT `id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `category_id`, `is_active` FROM lot'  ;
    $result_lot = mysqli_query($con, $sql_lot);

    if ($result && $result_lot) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);

    } else {
        $error = mysqli_error($con);
        $content = include_template('error.php', ['error' => $error]);
    }
}

//  SELECT c.name FROM category c JOIN lot l ON c.name = l.category_id;
