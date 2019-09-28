<?php
require_once('inc/functions.php');
require_once('inc/init.php');

$is_auth = rand(0, 1);
$user_name = "Вадим"; // укажите здесь ваше имя

//$sql_category = 'SELECT `id`, `name` FROM category';
//$categories = get_db_assoc($con, $sql_category);


$sql = 'SELECT * FROM category';
$result = mysqli_query($con, $sql);
$cats_ids = [];

if ($result) {
    $cats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $cats_ids = array_column($cats, 'id');
}

//проверяем метод отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Скопируем POST массив в переменную $lot
    $lot = $_POST;

//    if (isset($_POST["name_lot"])) {
//        $name_lot = trim($_POST["name_lot"]);
//    }
//    if (empty($_POST["name_lot"])) {
//        $name_lot = trim($_POST["name_lot"])
//    };

// if (empty(trim($_POST["name_lot"]))) {
//        $name_lot = $_POST["name_lot"];
//    };


    if (!empty($_POST["name_lot"])) {
        $name_lot_ = trim($_POST["name_lot"]);

        if(!empty($name_lot)){
            $name_lot = 'Ошибка валидации';
        }
    }

    var_dump($name_lot);

    if (!empty($_POST["description"])) {
        trim($_POST["description"]);

        if(!empty(trim($_POST["description"]))) {
            $description = $_POST["description"];
        }
    }


// 1) проверяешь есть ли элемент в массиве, не пустой ли он(если empty)
//2)делаешь трим
//3)снова проверяеш не пустой ли
//делай все последовательно не усложняй насрать на вложенность условий


    //*$name_lot = $_POST["name_lot"];
    //$description = $_POST["description"];
    $initial_price = $_POST["initial_price"];
    $expiration_date = $_POST["expiration_date"];
    $step_rate = $_POST["step_rate"];
    $category_id = $_POST["category_id"];

    //определяем список полей, которые собираемся валидировать
    $required = ['name_lot', 'description', 'initial_price', 'step_rate', 'expiration_date', 'category_id'];

    //определяем пустой массив $errors, который будем заполнять ошибками валидации
    $errors = [];

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
        'initial_price' => function () use ($initial_price) {
            if ((!is_numeric($initial_price)) and ($initial_price > 0)) {
                return "Начальная цена должна быть числом";
            }
            return null;
        },
        'step_rate' => function () use ($step_rate) {
            if ((!is_numeric($step_rate)) and ($step_rate > 0)) {
                return "Шаг ставки должен быть числом ";
            }
            return null;
        },
        'expiration_date' => function () use ($expiration_date) {
            return is_date_valid('expiration_date');
        }
    ];

    //Применяем функции ко всем полям формы
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];

            /*Результат работы функций записывается в массив ошибок*/
            $errors[$key] = $rule();
        }
    }

    //массив отфильтровываем, чтобы удалить от туда пустые значения и оставить только сообщения об ошибках
    $errors = array_filter($errors);

    /*проверяем существование каждого поля в списке обязательных к заполнению*/
    foreach ($required as $key) {
        if (empty($_POST[$key])) {

            //если поле не заполнено, то добавляем ошибку валидации в список ошибок
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

//Проверим, был ли загружен файл
    if (isset($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($tmp_name);
        $file_name = $_FILES['image']['name'];


        //Если тип загруженного файла не является jpeg, то добавляем новую ошибку в список ошибок валидации
        if ($file_type != "image/jpeg" or $file_type != "image/png") {
            $errors['file'] = 'Загрузите картинку в формате jpeg или png';
        }

        /*создание нового имени файла*/
        if ($file_type == "image/jpeg") {
            $filename = uniqid() . '.jpeg';
        } elseif ($file_type == "image/png") {
            $filename = uniqid() . '.png';
        }

        //        Если файл jpeg, то мы копируем его в директорию где лежат все изображения и добавляем путь
        //        к загруженному изображению в массив $lot
        else {
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $lot['image'] = $filename;
            //           $image = $lot['image'];
            var_dump($lot);
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

$add_tpl = include_template('add_tpl.php', [
    'cats' => $cats,
    'lot' => $lot,
    'user_name' => $user_name,
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'file_name' => $file_name
]);

print($add_tpl);
