<?php

function validateEmail($email, $min, $max)
{
    $result = '';
    $len = mb_strlen($_POST[$email]);
    if ($len < $min || $len > $max) {
        $result = 'Длинна E-mail должна быть от' . $min . 'до' . $max . 'символов';
    }
    if (!filter_var($_POST[$email], FILTER_VALIDATE_EMAIL)) {
        $result = 'Email должен быть корректным';
    }
    return $result;
}

/**
 * функцию, для получения значения поля
 * @param string $name принимает значение
 * @return string возвращает заполненные поля
 */
function getPostVal($name) {
    return $_POST[$name] ?? "";
}

function validateFilled($name) {
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }

    return null;
}

function validateCategory($name, $allowed_list) {
    $id = $_POST[$name];

    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

function validateLength($name, $min, $max) {
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }

    return null;
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}


/**
 * Проверяет условие, чтобы дата размещения была на 24 больше размещения ставок
 * Возращает ошибку если число не соотвествует формату ГГГГ-ММ-ДД
 *
 * @param string $date ключ масива даты окончания ставок
 * @return string содержание ошибки валидации даты размещения лота
 */
function validateTimeFormat($date)
{
    $date_now = time();
    $date_bate = strtotime($date);
    $date_diff = $date_bate - $date_now;
    $result = '';
    if (is_date_valid($date)) {
        $result = 'Введите число в формате ГГГГ-ММ-ДД';
    } elseif ($date_diff < 86400) {
        $result = 'Дата окончания торгов не может быть раньше через чем 24 часа';
    }
    return $result;
}

/**
 * Возвращает запрос из БД в виде ассоциативного массива
 * @param string $link ресурс соединения
 * @param string $sql запрос к базе данных
 * @return array ассоциативный массив из БД или страницу ошибки
 */
function get_db_assoc($link, $sql) {
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        return include_template('error.php', ['error' => $error]);
    }
}

/**
 * Определяет минимальную стаку
 * @param int $price Текущая цена
 * @param int $step Шаг ставки
 * @return int Минимальная ставка
 */
function min_bid($price, $step)
{
    return $price + $step;
}

/**
 * Определяет сколько осталось часов и минут до завершения лота
 * @param string $end_date Принимает дату завершения лота
 * @return array возвращает массив с часами и минутами
 */

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

function get_dt_range($end_date)
{
    $ts_lot_end = strtotime($end_date);
    $secs_to_end = $ts_lot_end - time();
    $hours = str_pad(floor($secs_to_end / 3600), 2, "0", STR_PAD_LEFT);
    $minutes = str_pad(floor(($secs_to_end % 3600) / 60), 2, "0", STR_PAD_LEFT);

    return [$hours, $minutes];
}


/**
 * Возращает оставшиеся время до окончания ставки в нужом формате 'ЧЧ ММ'
 *
 * @param string $final_date дата окончания приёма ставок
 * @return array|string дата в формате 'ЧЧ' 'ММ'
 */
function stop_time($final_date)
{
    $time = '';
    $date_now = date_create('now');
    $date_bate = date_create($final_date);
    $date_diff = date_diff($date_bate, $date_now);
    $hour = date_interval_format($date_diff, '%d %H %I');

    $time = explode(' ', $hour);
    $days_left = $time[0];


    if ($days_left > 0) {
        $time[1] = ($days_left * 24) + $time[1];
    }
    return $time;
}

/**
 * Выполняет запрос к БД и возвращает результат в виде ассоциативного массива.
 *
 * @param mysqli $dbConnection Подключение к БД
 * @param string $sqlQuery Строка запроса
 * @return array Массив записей
 */
function dbFetchData(mysqli $dbConnection, string $sqlQuery): array
{
    $sqlResult = mysqli_query($dbConnection, $sqlQuery);

    if (!$sqlResult) {
        exit('Ошибка при работе с БД: ' . mysqli_error($dbConnection));
    }

    return mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
}


/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
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
function esc($str)
{
    $text = htmlspecialchars($str);
    //$text = strip_tags($str);

    return $text;
}

/**
 * Форматирует число ставки лота
 * @param int $bet принимает число
 * @return int возвращает форматированное чило и добавляет в конце символ рубля
 */
function user_bet($bet)
{
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
function show_date($timestamp)
{
    $dt = date_create();
    $dt = date_timestamp_set($dt, $timestamp);

    $format = date_format($dt, "d.m.Y H:i");

    return $format;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Возращает CSS класс статус сделанной ставки елси аукцион по лоту завершился
 * или ставка выигрыла
 *
 * @param string $date_finish дата окончания ставки
 * @param int $winner_id выигрывший пользователь(его ID)
 * @return string возращает CSS класс
 */
function get_status_user_bet($date_finish, $winner_id)
{
    $result = '';
    if ((strtotime($date_finish) < time()) && ($winner_id === null)) {
        $result = 'rates__item--end';
    } elseif ((strtotime($date_finish) > time()) && ($winner_id === $_SESSION['user']['id'])) {
        $result = 'rates__item--win';
    }
    return $result;
}

/**
 * Приводит дату размещения ставки в человека читаеммый формат
 *
 * @param string $date_pub ключ массива указывающий на дату размещения ставки
 * @return string возращает отформатированную строку
 */
function get_relative_format($date_pub)
{
    $date_pub = strtotime($date_pub);
    $date_now = time();
    $date_diff = $date_now - $date_pub;
    if ($date_diff < 3600) {
        $params = array(
            'sec' => 60,
            'singular' => ' минута',
            'genitive' => ' минуты',
            'plural' => ' минут'
        );
    } elseif ($date_diff >= 3600 && $date_diff <= 86400) {
        $params = array(
            'sec' => 3600,
            'singular' => ' час',
            'genitive' => ' часа',
            'plural' => ' часов'
        );
    } elseif ($date_diff > 86400 && $date_diff <= 604800) {
        $params = array(
            'sec' => 86400,
            'singular' => ' день',
            'genitive' => ' дня',
            'plural' => ' дней'
        );
    } elseif ($date_diff > 604800 && $date_diff <= 3024000) {
        $params = array(
            'sec' => 604800,
            'singular' => ' неделя',
            'genitive' => ' недели',
            'plural' => ' недель'
        );
    } elseif ($date_diff > 3024000) {
        $params = array(
            'sec' => 3024000,
            'singular' => ' месяц',
            'genitive' => ' месяца',
            'plural' => ' месяцев'
        );
    }
    $date_create = floor($date_diff / $params['sec']);
    $result = $date_create . get_noun_plural_form($date_create, $params['singular'], $params['genitive'],
            $params['plural']) . ' назад';
    return $result;
}
