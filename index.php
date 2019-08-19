<?php

require_once('templates/functions.php');
require_once('templates/data.php');


$page_content = include_template('main.php', [
    'categories' => $categories,
    'ads' => $ads
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Главная страница'
]);

print($layout_content);
