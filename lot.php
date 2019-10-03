<?php
require_once('inc/functions.php');
require_once('inc/init.php');

if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
        ['error' => 'Ошибка 404: Страница не найдена']);
    print($content);
    die();
}
$result = '';
$cur_id = '';

if (isset($_GET['id'])) {
    $cur_id = (int)$_GET['id'];
}

$sql_category = 'SELECT `class`, `name` FROM category';
$result_category = mysqli_query($con, $sql_category);

$sql_lot = "SELECT lot.id, lot.user_id AS lot_user_id, lot.name, lot.description, lot.start_price, lot.bid_step, lot.img_path, lot.finish_date, category.name AS category_name FROM lot
    JOIN category ON lot.category_id = category.id
    WHERE lot.id = {$cur_id}";

$result_lot = mysqli_query($con, $sql_lot);

if ($result_category && $result_lot) {
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    $lot = mysqli_fetch_assoc($result_lot);

    $sql = "SELECT MAX(b.price) AS sum_bet, COUNT(b.id) AS total_bet FROM bid b WHERE lot_id = {$cur_id}";

    $result_bet = mysqli_query($con, $sql);

    if ($result_bet) {
        $sum_bet = mysqli_fetch_assoc($result_bet);
        if (empty($sum_bet['sum_bet'])) {
            $sum_bet['sum_bet'] = $lot['start_price'];
        }
    } else {
        $error = mysqli_error($con);
        echo $error;
    }

    $sql = "SELECT b.user_id, u.username, b.price AS bid_price, b.date AS bid_date FROM bid b JOIN users u ON b.user_id = u.id
            WHERE b.lot_id = {$cur_id} ORDER BY b.date DESC";

    $result_history_bet = mysqli_query($con, $sql);

    if ($result_history_bet) {

        $history_users_bet = mysqli_fetch_all($result_history_bet, MYSQLI_ASSOC);

    } else {
        $error = mysqli_error($con);
        echo $error;
    }

    if (isset($_SESSION['user'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $min_bet = $lot['bid_step'] + $sum_bet['sum_bet'];

            $required = ['cost'];

            $rules = [
                'cost' => function () use ($min_bet) {
                    return check_sum_bet('cost', $min_bet);
                }
            ];

            foreach ($_POST as $key => $value) {
                if (isset($rules[$key])) {
                    $rule = $rules[$key];
                    $errors[$key] = $rule();
                }
            }

            foreach ($required as $key) {
                if (!isset($_POST[$key]) || (trim($_POST[$key]) === '')) {
                    $errors[$key] = 'Это поле надо заполнить';
                }
            }

            $errors = array_filter($errors);

            if (count($errors)) {
                $page_content = include_template('lot_tpl.php', [
                    'navigation' => $navigation,
                    'lot' => $lot,
                    'categories' => $categories,
                    'sum_bet' => $sum_bet,
                    'errors' => $errors,
                    'history_users_bet' => $history_users_bet,
                ]);
            } else {
                $bid['price'] = $_POST['cost'];
                $bid['user_id'] = $_SESSION['user']['id'];
                $bid['lot_id'] = $lot['id'];

                $sql = 'INSERT INTO bid (price, user_id, lot_id)
                        VALUES (?, ?, ?)';

                $stmt = db_get_prepare_stmt($con, $sql, $bid);
                $res = mysqli_stmt_execute($stmt);

                if ($res) {
                    $lot_id = $lot['id'];

                    header('Location:lot.php?id=' . $lot_id);
                }
            }
        } else {
            $page_content = include_template('lot_tpl.php', [
                'navigation' => $navigation,
                'categories' => $categories,
                'lot' => $lot,
                'sum_bet' => $sum_bet,
                'history_users_bet' => $history_users_bet,
            ]);
        }
    } else {
        $page_content = include_template('lot_tpl.php', [
            'navigation' => $navigation,
            'categories' => $categories,
            'lot' => $lot,
            'sum_bet' => $sum_bet,
            'history_users_bet' => $history_users_bet
        ]);
    }

} else {
    $error = mysqli_error($con);
    $page_content = include_template('error.php', ['error' => $error]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'title' => 'Тайтл лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);

