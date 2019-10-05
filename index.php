<?php
require_once __DIR__ . '/starter.php';
// require_once 'getwinner.php';

//Необходимо вначале установить библиотеку swiftmailer
//после чего можно раскомментировать подключение сценария getwinner.php

$dbConnection = dbConnect($config['db']);

$categories = getCategories($dbConnection);
$lots = getActiveLots($dbConnection);

dbClose($dbConnection);

$navigation = includeTemplate('navigation.php', ['categories' => $categories]);
$mainContent = includeTemplate('main.php', ['categories' => $categories, 'lots' => $lots]);
$layoutContent = includeTemplate(
    'layout.php',
    [
        'pageTitle' => 'Главная',
        'isAuth' => (bool)$sessUser,
        'userName' => $sessUser['name'] ?? '',
        'navigation' => $navigation,
        'mainContent' => $mainContent,
        'index' => true
    ]
);
print($layoutContent);
