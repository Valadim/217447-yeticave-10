<?php
require_once('vendor/autoload.php');
require_once('inc/functions.php');
require_once('inc/init.php');

$sql = 'SELECT * FROM lot l WHERE finish_date < NOW()
AND winner_id IS NULL';
$result = mysqli_query($con, $sql);

$lots_win = [];

if (!empty($result)) {
    $lots_finished = mysqli_fetch_all($result, MYSQLI_ASSOC);

//    var_dump($lots_finished);

    $winners_id = [];
    foreach ($lots_finished as $lots_finish) {
        $sql = "SELECT * FROM bid WHERE lot_id = {$lots_finish['id']} ORDER BY date DESC LIMIT 1";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $winners_id[] = mysqli_fetch_assoc($result);
        } else {
            $error = mysqli_error($con);
            echo $error;
        }
    }

    //   var_dump($winners_id);

    $winners_id = array_filter($winners_id);

    foreach ($winners_id as $winner_id) {
        $sql = "UPDATE lot SET winner_id = {$winner_id['user_id']} WHERE id = {$winner_id['lot_id']}";
        $result = mysqli_query($con, $sql);
    }
}


$sql = 'SELECT l.winner_id, u.username, l.id AS lot_win_id, l.name, u.email
        FROM lots l JOIN users u ON l.winner_id = u.id WHERE winner_id IS NOT NULL';


$result = mysqli_query($con, $sql);


if ($result && mysqli_num_rows($result)) {
    $lots_win = mysqli_fetch_all($result, MYSQLI_ASSOC);

    var_dump($lots_win);
}

$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');

$mailer = new Swift_Mailer($transport);

foreach ($lots_win as $lot_win) {
    $recipient = [];
    $recipient[$lot_win['email']] = $lot_win['user_name'];

    $message = new Swift_Message();
    $message->setSubject('Ваша ставка победила');
    $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
    $message->setBcc($recipient);
    $msg_content = include_template('email.php', [
        'lot_win' => $lot_win,
    ]);
    $message->setBody($msg_content, 'text/html');
    $result = $mailer->send($message);

    unset($recipient);
}


