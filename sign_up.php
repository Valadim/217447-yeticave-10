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
            return validateLength('name', 4, 24);
        },
        'password' => function () {
            return validateLength('password', 4, 64);
        },
        'message' => function () {
            return validateLength('message', 4, 500);
        },
    ];

    foreach ($required as $key) {

        if (!empty($_POST[$key])) {
            $_POST[$key] = trim($_POST[$key]);
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            } else {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        } else {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($con, $user['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }
    $errors = array_filter($errors);

    //массив отфильтровываем, чтобы удалить от туда пустые значения и оставить только сообщения об ошибках
    $errors = array_filter($errors);

    $sql = 'INSERT INTO users (email, password, username, contacts) VALUES (?, ?, ?, ?)';

    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

    $stmt = db_get_prepare_stmt($con, $sql, [
        $user['email'],
        $user['password'],
        $user['name'],
        $user['message']
    ]);
    $res = mysqli_stmt_execute($stmt);



    if ($res) {
        $lot_id = mysqli_insert_id($con);
        header("Location: login.php");
    } else {
        $page_content = include_template('error.php', ['error' => mysqli_error($con)]);
    }

}

$navigation = include_template('main_nav.php', ['categories' => $categories]);

$page_content = include_template('sign_up_tpl.php', [
    'navigation' => $navigation,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Регистрация аккаунта',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
