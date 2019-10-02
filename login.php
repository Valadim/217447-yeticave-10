<?php

require_once('inc/functions.php');
require_once('inc/init.php');

$errors = [];

//
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//
//    $form = $_POST;
//
//    $required = ['email', 'password'];
//
//    $rules = [
//        'email' => function () {
//            return validateEmail('email', 4, 24);
//        },
//        'password' => function () {
//            return validateLength('password', 4, 64);
//        }
//    ];
//
//    foreach ($required as $key) {
//
//        if (!empty($_POST[$key])) {
//            $_POST[$key] = trim($_POST[$key]);
//            if (empty($_POST[$key])) {
//                $errors[$key] = 'Это поле надо заполнить';
//            } else {
//                $rule = $rules[$key];
//                $errors[$key] = $rule();
//            }
//        } else {
//            $errors[$key] = 'Это поле надо заполнить';
//        }
//    }
//
//    $email = mysqli_real_escape_string($con, $form['email']);
//    $sql = "SELECT * FROM users WHERE email = '$email'";
//    $res = mysqli_query($con, $sql);
//
//    var_dump($res);
//
//    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
//
//    if (!count($errors) and $user) {
//        if (password_verify($form['password'], $user['password'])) {
//            $_SESSION['user'] = $user;
//        } else {
//            $errors['password'] = 'Неверный пароль';
//        }
//    } else {
//        $errors['email'] = 'Такой пользователь не найден';
//    }
//
//    if (count($errors)) {
//        $page_content = include_template('enter.php', ['form' => $form, 'errors' => $errors]);
//    } else {
//        header("Location: /index.php");
//        exit();
//    }
//
//} else {
//        $page_content = include_template('enter.php', []);
//
//        if (isset($_SESSION['user'])) {
//            header("Location: /index.php");
//            exit();
//        }
//    }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($con, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('login_tpl.php', [ 'navigation' => $navigation, 'errors' => $errors]);
    }
    else {
        header("Location: /index.php");
        exit();
    }
}
else {
    $page_content = include_template('login_tpl.php', [
        'navigation' => $navigation,
        'errors' => $errors
    ]);

    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Вход на сайт',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
