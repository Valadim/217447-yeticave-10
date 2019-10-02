<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST;

    //определяем список полей, которые собираемся валидировать
    $required = ['email', 'password', 'name', 'message'];

    $rules = [
        'email' => function () {
            return validateEmail('email', 4, 24);
        },
        'name' => function () {
            return validateText('name', 4, 24);
        },
        'password' => function () {
            return validateText('password', 4, 64);
        },
        'message' => function () {
            return validateText('message', 4, 500);
        },
    ];



    //Определим функции-помощники для валидации и поля, которые они должны обработать
//    $rules = [
//        'email' => function () {
//            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
//                return 'Email должен быть корректным';
//            }
//            return null;
//        },
//        'password' => function () {
//            return validateLength('password', 4, 64);
//        },
//        'name' => function () {
//            return validateLength('name', 4, 64);
//        },
//        'message' => function () {
//            return validateLength('message', 4, 500);
//        }
//    ];

    foreach ($required as $key) {

        if (!empty($_POST[$key])) {
            $_POST[$key] = trim($_POST[$key]);

            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            } else {
                $rule = $rules[$key];

                /*Результат работы функций записывается в массив ошибок*/
                $errors[$key] = $rule();
            }
        } else {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    //массив отфильтровываем, чтобы удалить от туда пустые значения и оставить только сообщения об ошибках
    $errors = array_filter($errors);


    //проверяем длину массива с ошибками
    if (count($errors)) {

        //если были ошибки, то показываем их пользователю вместе с формой
        $page_content = include_template('add_tpl.php', ['errors' => $errors]);
    } else {

        $sql = 'INSERT INTO users (email, password, username, contacts) VALUES (?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($con, $sql, [
            $user['email'],
            $user['password'],
            $user['name'],
            $user['message']
        ]);
        $res = mysqli_stmt_execute($stmt);


        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php");
        } else {
            $content = include_template('error.php', ['error' => mysqli_error($con)]);
            print_r($user);
        }
    }
}

$navigation = include_template('main_nav.php', ['categories' => $categories]);

$sign_up_tpl = include_template('sign_up_tpl.php', [
    'navigation' => $navigation,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $sign_up_tpl,
    'navigation' => $navigation,
    'title' => 'Регистрация аккаунта',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
