<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Удаляет HTML символы или заменяет их на мнемоники
 * @param string $str принимает строку в которой нужно удалить теги
 * @return string возвращает строку без тегов или с заменой на мнемоники
 */
function esc($str) {
    $text = htmlspecialchars($str);
    //$text = strip_tags($str);

    return $text;
}

/**
 * Форматирует число ставки лота
 * @param int $bet принимает число
 * @return int возвращает форматированное чило и добавляет в конце символ рубля
 */
function user_bet($bet) {
    $html = "<b class=\"rub\"> Р</b>";
    if ($bet >= 1000) {
        return number_format(ceil($bet), 0, ',', " ") . $html;
    }
    return ceil($bet) . $html;
}

/**
 * Форматирует дату в формат ДД:ММ:ГГГГ ЧЧ:ММ
 * @param int $timestamp принимает дату
 * @return int возвращает форматированную дату
 */
function show_date($timestamp){
    $dt = date_create();
    $dt = date_timestamp_set($dt, $timestamp);

    $format = date_format($dt, "d.m.Y H:i");

    return $format;
}

