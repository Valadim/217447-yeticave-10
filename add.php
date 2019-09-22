<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

//$sql_category = 'SELECT `id`, `name` FROM category';
//$categories = get_db_assoc($con, $sql_category);

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($con, $sql);

$cats_ids = [];

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $cats_ids = array_column($categories, 'id');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lot = $_POST;

    $required = ['name', 'category_id', 'description', 'img_path', 'start_price', 'bid_step', 'finish_date'];
    $errors = [];

    $rules = [
        'category_id' => function () use ($cats_ids) {
            return validateCategory('category_id', $cats_ids);
        },
        'title' => function () {
            return validateLength('title', 10, 200);
        },
        'description' => function () {
            return validateLength('description', 10, 3000);
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    if (isset($_FILES['img_path']['name'])) {
        $tmp_name = $_FILES['img_path']['tmp_name'];
        $path = $_FILES['img_path']['name'];
        $filename = uniqid() . '.jpg';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/gif") {
            $errors['file'] = 'Загрузите картинку в формате GIF';
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $gif['path'] = $filename;
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    if (count($errors)) {
        $page_content = include_template('add.php', ['gif' => $gif, 'errors' => $errors, 'categories' => $categories]);
    } else {
        $sql = 'INSERT INTO lot (user_id, name, category_id, description, start_price, bid_step, finish_date, img_path ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($con, $sql,
            [
                1,
                $lot['lot-name'],
                $lot['category'],
                $lot['message'],
                $lot['lot-rate'],
                $lot['lot-step'],
                $lot['lot-date'],
                $lot['img_path']
            ]);

        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($con);

            header("Location: lot.php?id=" . $lot_id);
        }
    }
} else {
    $content = include_template('error.php', ['error' => mysqli_error($con)]);
    //print_r($lot);
}


$add_tpl = include_template('add_tpl.php', [
    'categories' => [],
    'user_name' => $user_name,
    'is_auth' => $is_auth
    //'classname' => 'form__item--invalid'

]);

print($add_tpl);



//После отправки формы выполните валидацию. Руководствуйтесь правилами, описанными в ТЗ.
//Если проверка формы выявила ошибки, то сделать следующее:
//- в тег формы добавить класс form--invalid;
//- для всех полей формы, где найдены ошибки:
//- добавить контейнеру с этим полем класс form__item--invalid;
//- в тег span.form__error этого контейнера записать текст ошибки. Например: «Заполните это поле».
//Если проверка прошла успешно, то сформировать и выполнить SQL запрос на добавление нового лота, а затем переадресовать пользователя на страницу просмотра этого лота


//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $lot = $_POST;
//    $filename = uniqid() . '.jpg';
//    $lot['img_path'] = $filename;
//    //$lot['lot-date'] = '2019-29-28 12:28:05';
//    move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $filename);
//
//    $sql = 'INSERT INTO lot (user_id, name, category_id, description, start_price, bid_step, finish_date, img_path ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
//
//    $stmt = db_get_prepare_stmt($con, $sql,
//        [
//            1,
//            $lot['lot-name'],
//            $lot['category'],
//            $lot['message'],
//            $lot['lot-rate'],
//            $lot['lot-step'],
//            $lot['lot-date'],
//            $lot['img_path']
//        ]);
//
//    $res = mysqli_stmt_execute($stmt);
//
//    if ($res) {
//        $lot_id = mysqli_insert_id($con);
//
//        header("Location: lot.php?id=" . $lot_id);
//    } else {
//        $content = include_template('error.php', ['error' => mysqli_error($con)]);
//        print_r($lot);
//    }
//}
