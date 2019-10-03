<?php
require_once('inc/functions.php');
require_once('inc/init.php');

if (isset($_SESSION['user'])) {


    $sql = "SELECT b.lot_id, l.img_path, l.id AS lot_id, l.name AS lot_name, l.user_id, u.contacts AS user_contacts, l.winner_id,
       c.name AS category_name, l.finish_date, MAX(b.price) AS max_my_bet, MAX(b.date) AS bid_date FROM bid b
       JOIN lot l ON b.lot_id = l.id
       JOIN category c ON l.category_id = c.id
       JOIN users u ON u.id = l.user_id
       WHERE b.user_id = {$_SESSION['user']['id']}
       GROUP BY b.lot_id";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $my_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
        echo $error;
    }

    $page_content = include_template('bets_tpl.php', [
        'navigation' => $navigation,
        'categories' => $categories,
        'my_bets' => $my_bets
    ]);

} else {
    http_response_code(403);
    header('Location: /login.php');
    exit();
}
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'navigation' => $navigation,
    'categories' => $categories,
    'title' => 'Мои ставки',
]);

print($layout_content);
