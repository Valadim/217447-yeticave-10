<?php

require_once('inc/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $category = $_GET['category'] ?? '';
    $category = trim($category);
    $category_query = $category;

    if (isset($category)) {

        $page_items = 9;
        $cur_page = $_GET['page'] ?? $_GET['page'] = 1;

        $sql = "SELECT COUNT(l.id) AS cnt FROM lot l
                JOIN category c ON l.category_id = c.id
                WHERE MATCH(l.name, l.description) AGAINST(?) AND finish_date > NOW()";
        $stmt = db_get_prepare_stmt($con, $sql, [$category]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $items_count = mysqli_fetch_assoc($result)['cnt'];
        $pages_count = ceil($items_count / $page_items);
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = "SELECT MAX(b.price) AS max_bid, COUNT(b.price) AS bid_count, l.id,
                l.date, l.name, l.img_path, l.start_price, c.name AS category_name, l.finish_date
                FROM lot l JOIN category c ON l.category_id = c.id
                LEFT JOIN bid b ON l.id = b.lot_id WHERE MATCH(l.name, l.description) AGAINST(?)
                AND l.finish_date > NOW() GROUP BY l.id
                ORDER BY l.date DESC LIMIT {$page_items} OFFSET {$offset}";

        $stmt = db_get_prepare_stmt($con, $sql, [$category]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (mysqli_num_rows($result) === 0) {

            $category = 'Ничего не найдено по вашему запросу: ' . $category;
        }

        $page_content = include_template('search_tpl.php', [
            'categories' => $categories,
            'navigation' => $navigation,
            'lots' => $lots,
            'search' => $category,
            'items_count' => $items_count,
            'pages' => $pages,
            'cur_page' => $cur_page,
            'pages_count' => $pages_count,
            'category_query' => $category_query
        ]);


    } else {
        $page_content = include_template('search_tpl.php', [
            'categories' => $categories,
        ]);
    }

} else {
    $page_content = include_template('search_tpl.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'categories' => $categories,
    'title' => 'Результаты поиска',
    'category_query' => $category_query
]);

print($layout_content);


