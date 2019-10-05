<?php
require_once __DIR__ . '/starter.php';
require __DIR__ . '/functions/mail.php';

$dbConnection = dbConnect($config['db']);

$winners = getWinners($dbConnection);

dbClose($dbConnection);

if ($winners) {
    sendMailToWinners($config['smtp'] + ['base_url' => $config['base_url']], $winners);
}
