<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

$sql_category = 'SELECT `class`, `name` FROM category';
$result_category = mysqli_query($con, $sql_category);

if ($result_category) {
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($con);
    $page_content = include_template('error.php', ['error' => $error]);
}


$add_tpl = include_template('add_tpl.php', [
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($add_tpl);
