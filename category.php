<?php

require_once('inc/init.php');

$sql_cat_id = "SELECT id, name, class FROM category WHERE id = {$_GET['cat_id']}";
$result_cat_id = mysqli_query($con, $sql_cat_id);
if ($result_cat_id) {
    $get_cat_id = mysqli_fetch_assoc($result_cat_id);
} else {
    $error = mysqli_error($con);
    $page_content = include_template('error.php', ['error' => $error]);
}

if (!isset($_GET['cat_id']) || !is_numeric($_GET['cat_id']) || $_GET['cat_id'] != $get_cat_id['id']) {
    http_response_code(404);
    $page_content = include_template('404.php', [
        'navigation' => $navigation,
        'categories' => $categories,
        'error' => 'Ошибка 404: Страница не найдена'
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'navigation' => $navigation,
        'title' => 'Ошибка 404: Страница не найдена',
        'user_name' => $user_name,
        'is_auth' => $is_auth
    ]);

    print($layout_content);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $category = $_GET['cat_id'] ?? '';
    $category = trim($category);

    if (isset($category)) {

        $page_items = 9;
        $cur_page = $_GET['page'] ?? $_GET['page'] = 1;

        $sql = "SELECT COUNT(l.id) AS cnt FROM lot l
                JOIN category c ON l.category_id = c.id
                WHERE c.id = {$_GET['cat_id']} AND finish_date > NOW()";

//        $stmt = db_get_prepare_stmt($con, $sql, [$category]);
//        mysqli_stmt_execute($stmt);
//        $result = mysqli_stmt_get_result($stmt);

        $result = mysqli_query($con, $sql);
        if ($result) {
            $items_count = mysqli_fetch_assoc($result)['cnt'];
            $pages_count = ceil($items_count / $page_items);
            $offset = ($cur_page - 1) * $page_items;
            $pages = range(1, $pages_count);

        } else {
            $error = mysqli_error($con);
            $page_content = include_template('error.php', ['error' => $error]);
        }

        $sql = "SELECT MAX(b.price) AS max_bid, COUNT(b.price) AS bid_count, l.id,
                l.date, l.name, l.img_path, l.start_price, c.name AS category_name, l.finish_date
                FROM lot l JOIN category c ON l.category_id = c.id
                LEFT JOIN bid b ON l.id = b.lot_id WHERE c.id = {$_GET['cat_id']}
                AND l.finish_date > NOW() GROUP BY l.id
                ORDER BY l.date DESC LIMIT {$page_items} OFFSET {$offset}";

        $stmt = db_get_prepare_stmt($con, $sql, [$category]);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (mysqli_num_rows($result) === 0) {

            $category = 'Товары из категории: ' . $category;
        }

        $page_content = include_template('category_tpl.php', [
            'categories' => $categories,
            'navigation' => $navigation,
            'lots' => $lots,
            'cat' => $get_cat_id,
            'items_count' => $items_count,
            'pages' => $pages,
            'cur_page' => $cur_page,
            'pages_count' => $pages_count,
            'category' => $category
        ]);

    } else {
        $page_content = include_template('category_tpl.php', [
            'categories' => $categories,
        ]);
    }

} else {
    $page_content = include_template('category_tpl.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'categories' => $categories,
    'title' => 'Результаты поиска'
]);

print($layout_content);
