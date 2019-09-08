
-- Запросы на добавление пользователей

INSERT INTO `users` (`id`, `email`, `username`, `password`, `contacts`, `create_time`)
VALUES (NULL, 'sakeyev@mail.ru', 'Вадим', '123456', 'Тут поле для контактов', CURRENT_TIMESTAMP);

INSERT INTO `users` (`id`, `email`, `username`, `password`, `contacts`, `create_time`)
VALUES (NULL, 'geralt@mail.ru', 'Геральт из Ривии', '284756', 'Каэр Морхен, строение 5, комната 3', CURRENT_TIMESTAMP);

INSERT INTO `users` (`id`, `email`, `username`, `password`, `contacts`, `create_time`)
VALUES (NULL, 'triss@mail.ru', 'Трисс Меригольд', '386756', 'Новиград, Обрезки, дом 12', CURRENT_TIMESTAMP);

INSERT INTO `users` (`id`, `email`, `username`, `password`, `contacts`, `create_time`)
VALUES (NULL, 'yennefer@mail.ru', 'Йеннифэр из Венгерберга', '2837456', 'Венгербер, а дальше ищи как хочешь', CURRENT_TIMESTAMP);

-- Запросы на добавление категорий

INSERT INTO `category` (`id`, `name`, `class`) VALUES (NULL, 'Доски и лыжи', 'boards');

INSERT INTO `category` (`id`, `name`, `class`) VALUES (NULL, 'Крепления', 'attachment');

INSERT INTO `category` (`id`, `name`, `class`) VALUES (NULL, 'Ботинки', 'boots');

INSERT INTO `category` (`id`, `name`, `class`) VALUES (NULL, 'Одежда', 'clothing');

INSERT INTO `category` (`id`, `name`, `class`) VALUES (NULL, 'Инструменты', 'tools');

INSERT INTO `category` (`id`, `name`, `class`) VALUES (NULL, 'Разное', 'other');

-- Запросы на добавление лотов

INSERT INTO `lot` (`id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `winner_id`, `category_id`, `is_active`)
VALUES (NULL, CURRENT_TIMESTAMP, '2014 Rossignol District Snowboard', 'Описание товара', '../img/lot-1.jpg', 10999, '2019-09-28 12:28:05', 1000, 1, NULL, 1, 1);

INSERT INTO `lot` (`id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `winner_id`, `category_id`, `is_active`)
VALUES (NULL, CURRENT_TIMESTAMP, 'DC Ply Mens 2016/2017 Snowboard', 'Описание товара', '../img/lot-2.jpg', 15999, '2019-09-12 09:28:05', 1000, '2', NULL, 1, 1);

INSERT INTO `lot` (`id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `winner_id`, `category_id`, `is_active`)
VALUES (NULL, CURRENT_TIMESTAMP, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Описание товара', '../img/lot-3.jpg', 8000, '2019-10-02 22:35:05', 1000, 3, NULL, 2, 1);

INSERT INTO `lot` (`id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `winner_id`, `category_id`, `is_active`)
VALUES (NULL, CURRENT_TIMESTAMP, 'Ботинки для сноуборда DC Mutiny Charocal', 'Описание товара', '../img/lot-4.jpg', 10999, '2019-09-29 02:08:05', 1000, 4, NULL, 3, 1);

INSERT INTO `lot` (`id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `winner_id`, `category_id`, `is_active`)
VALUES (NULL, CURRENT_TIMESTAMP, 'Куртка для сноуборда DC Mutiny Charocal', 'Описание товара', '../img/lot-5.jpg', 10999, '2019-09-14 19:00:00', 1000, 3, NULL, 4, 1);

INSERT INTO `lot` (`id`, `date`, `name`, `description`, `img_path`, `start_price`, `finish_date`, `bid_step`, `user_id`, `winner_id`, `category_id`, `is_active`)
VALUES (NULL, CURRENT_TIMESTAMP, 'Маска Oakley Canopy', 'Описание товара', '../img/lot-6.jpg', 5500, '2019-10-08 11:36:47', 1000, 1, NULL, 6, 1);

-- Запросы на добавление ставки

INSERT INTO `bid` (`id`, `date`, `price`, `user_id`, `lot_id`) VALUES (NULL, CURRENT_TIMESTAMP, 20000, 2, 1);

INSERT INTO `bid` (`id`, `date`, `price`, `user_id`, `lot_id`) VALUES (NULL, CURRENT_TIMESTAMP, 22000, 3, 1);

INSERT INTO `bid` (`id`, `date`, `price`, `user_id`, `lot_id`) VALUES (NULL, CURRENT_TIMESTAMP, 8000, 2, 6);

-- получить все категории

SELECT * FROM `category`;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;

SELECT lot.name, lot.start_price, lot.img_path, category.name AS category_name, MAX(bid.price) AS current_price, COUNT(bid.lot_id) AS bid_count FROM lot
LEFT JOIN category ON lot.category_id = category.id
LEFT JOIN bid ON lot.id = bid.lot_id
WHERE lot.finish_date > NOW() AND lot.winner_id IS NULL
GROUP BY lot.id
ORDER BY lot.date DESC;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот

SELECT lot.*, category.name AS category_name FROM lot
JOIN category ON lot.category_id = category.id
WHERE lot.id = 1;

-- обновить название лота по его идентификатору

UPDATE lot SET lot.name = '2014 Rossignol District Snowboard редакция 1'
WHERE lot.id = 1;

-- получить список ставок для лота по его идентификатору с сортировкой по дате.

SELECT bid.* FROM lot
JOIN bid ON lot.id = bid.lot_id
WHERE lot.id = 1
ORDER BY bid.date DESC;
