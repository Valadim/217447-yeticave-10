<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$sql = 'SELECT * FROM category';
$result = mysqli_query($con, $sql);
$cats_ids = [];

if ($result) {
    $cats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $cats_ids = array_column($cats, 'id');
}
$errors = [];
$lot = [];

//проверяем метод отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Скопируем POST массив в переменную $lot
    $lot = $_POST;

    //определяем список полей, которые собираемся валидировать
    $required = ['name_lot', 'description', 'initial_price', 'step_rate', 'expiration_date', 'category_id'];

    //Определим функции-помощники для валидации и поля, которые они должны обработать
    $rules = [
        'category_id' => function () use ($cats_ids) {
            return validateCategory('category_id', $cats_ids);
        },
        'name_lot' => function () {
            return validateLength('name_lot', 10, 128);
        },
        'description' => function () {
            return validateLength('description', 10, 2000);
        },
        'initial_price' => function () {
            if ((!is_numeric($_POST["initial_price"])) and ($_POST["initial_price"] > 0)) {
                return "Начальная цена должна быть числом";
            }
            return null;
        },
        'step_rate' => function () {
            if ((!is_numeric($_POST["step_rate"])) and ($_POST["step_rate"] > 0)) {
                return "Шаг ставки должен быть числом";
            }
            return null;
        },
        'expiration_date' => function () {
            if (!is_date_valid($_POST['expiration_date'])) {
                return 'Введите число в формате ГГГГ-ММ-ДД';
            } else {
                $date_now = strtotime('now');
                $date_end = strtotime($_POST['expiration_date']);

                if ($date_end <= $date_now) {
                    return 'Дата окончания торгов не может быть раньше чем завтра';
                }
            }
            return null;
        }
    ];

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

//Проверим, был ли загружен файл
    if (!empty($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($tmp_name);
        $file_name = $_FILES['image']['name'];

        //Если тип загруженного файла не является jpeg, то добавляем новую ошибку в список ошибок валидации
        if ($file_type != "image/jpeg" && $file_type != "image/png") {
            $errors['file'] = 'Загрузите картинку в формате jpeg или png';
        }

    } //если файл не был загружен, добавляем ошибку
    else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    //проверяем длину массива с ошибками
    if (count($errors)) {

        //если были ошибки, то показываем их пользователю вместе с формой
        $page_content = include_template('add_tpl.php', [
            'lot' => $lot,
            'errors' => $errors,
            'cats' => $cats
        ]);
    } else {

        /*создание нового имени файла*/
        if ($file_type == "image/jpeg") {
            $filename = uniqid() . '.jpeg';
        } else {
            $filename = uniqid() . '.png';
        }

        $tmp_name = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        move_uploaded_file($tmp_name, 'uploads/' . $filename);
        $lot['image'] = $filename;

        $sql = 'INSERT INTO lot (user_id, name, category_id, description, start_price, bid_step, finish_date, img_path ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($con, $sql, [
            1,
            $lot['name_lot'],
            $lot['category_id'],
            $lot['description'],
            $lot['initial_price'],
            $lot['step_rate'],
            $lot['expiration_date'],
            $lot['image']
        ]);
        $res = mysqli_stmt_execute($stmt);


        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            $content = include_template('error.php', ['error' => mysqli_error($con)]);
            print_r($lot);
        }
    }
}

$navigation = include_template('main_nav.php', ['categories' => $categories]);

$add_tpl = include_template('add_tpl.php', [
    'cats' => $cats,
    'navigation' => $navigation,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $add_tpl,
    'navigation' => $navigation,
    'title' => 'Добавление лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);


//function ()
//{
//    if (!is_date_valid($_POST['expiration_date'])) {
//        return 'Введите число в формате ГГГГ-ММ-ДД';
//    } else {
//        $date_now = strtotime('now');
//        $date_end = strtotime($_POST['expiration_date']);
//
//        if ($date_end <= $date_now) {
//            return 'Дата окончания торгов не может быть раньше через завтра';
//        }
//    }
//    return null;
//}


