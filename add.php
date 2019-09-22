<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $img = $_POST;

    $filename = uniqid() . '.jpg';
    $img['path'] = $filename;
    move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $filename);

    $sql = 'INSERT INTO lot (date, category_id, user_id, name, description, img_path, start_price, bid_step, finish_date ) VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($con, $sql, $img);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        $lot_id = mysqli_insert_id($con);

        header("Location: lot.php?id=" . $lot_id);
    }
    else {
        $content = include_template('error.php', ['error' => mysqli_error($con)]);
    }
}


$sql_category = 'SELECT `class`, `name` FROM category';
$categories = get_db_assoc($con, $sql_category);

$add_tpl = include_template('add_tpl.php', [
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($add_tpl);



//После отправки формы выполните валидацию. Руководствуйтесь правилами, описанными в ТЗ.
//Если проверка формы выявила ошибки, то сделать следующее:
//- в тег формы добавить класс form--invalid;
//- для всех полей формы, где найдены ошибки:
//- добавить контейнеру с этим полем класс form__item--invalid;
//- в тег span.form__error этого контейнера записать текст ошибки. Например: «Заполните это поле».
//Загруженный файл изображения переместите в папку uploads.
//Если проверка прошла успешно, то сформировать и выполнить SQL запрос на добавление нового лота, а затем переадресовать пользователя на страницу просмотра этого лота
