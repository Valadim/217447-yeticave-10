<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

$sql_category = 'SELECT `id`, `name` FROM category';
$categories = get_db_assoc($con, $sql_category);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $filename = uniqid() . '.jpg';
    $lot['img_path'] = $filename;
    $lot['lot-date'] = '2019-29-28 12:28:05';
    move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $filename);

    $sql = 'INSERT INTO lot (date, user_id, name, category_id, description, start_price, bid_step, finish_date, img_path ) VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)';

    //                                     INSERT         VALUES
    //  [lot-create] => NOW()              date           NOW()
    //  [lot-user] => 1                    user_id        1
    //  [lot-name] => Panasonic            name           ?
    //  [category] => 2                    category_id    ?
    //  [message]  => Текст описания       description    ?
    //  [lot-rate] => 1000                 start_price    ?
    //  [lot-step] => 500                  bid_step       ?
    //  [lot-date] => 2019-09-29           finish_date    ?
    //  [img_path] => 5d8769d59fd69.jpg    img_path       ?
    //  [lot-date] => 2019-09-28 12:28:05


    $stmt = db_get_prepare_stmt($con, $sql, $lot);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        $lot_id = mysqli_insert_id($con);

        header("Location: lot.php?id=" . $lot_id);
    }
    else {
        $content = include_template('error.php', ['error' => mysqli_error($con)]);
        print_r($lot);
    }
}


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
