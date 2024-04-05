CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `description` text,
  `time` int,
  `author_id` int,
  `calories` float,
  `proteins` float,
  `carbs` float,
  `fats` float
);

CREATE TABLE IF NOT EXISTS`products` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `calories` float,
  `proteins` float,
  `carbs` float,
  `fats` float,
  `category_id` int,
  `measure_id` int
);

CREATE TABLE IF NOT EXISTS`recipe_product` (
  `recipe_id` int,
  `product_id` int,
  `value` float
);

CREATE TABLE IF NOT EXISTS `measures` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255)
);

CREATE TABLE IF NOT EXISTS `user_product` (
  `user_id` int,
  `product_id` int,
  `value` float
);

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255)
);

CREATE TABLE IF NOT EXISTS `images` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `path` varchar(255),
  `recipe_id` int,
  `is_cover` tinyint(1)
);

CREATE TABLE IF NOT EXISTS `planner` (
  `recipe_id` int,
  `user_id` int,
  `course_id` int,
  `date` date
);

CREATE TABLE IF NOT EXISTS `course` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255)
);

CREATE TABLE IF NOT EXISTS `featured` (
  `user_id` int,
  `recipe_id` int
);
INSERT INTO categories(title)
VALUES
    ('Мясо'),
    ('Рыба'),
    ('Птица'),
    ('Морепродукты'),
    ('Овощи'),
    ('Фрукты'),
    ('Зелень'),
    ('Грибы'),
    ('Яйца/Яичные прдукты'),
    ('Молоко/Молочные продукты'),
    ('Соя/Соевые продукты'),
    ('Жиры/Масла'),
    ('Орехи'),
    ('Крупы/Злаки'),
    ('Семена'),
    ('Мука/Мучные продукты'),
    ('Специи/Пряности'),
    ('Сладости'),
    ('Напитки/Соки');
INSERT INTO measures(title)
VALUES
('гр'),
('кг'),
('чн. лжк.'),
('ст. лжк.'),
('л'),
('мл'),
('шт'),
('По вкусу');
INSERT INTO `products`(`name`, `calories`, `proteins`, `carbs`, `fats`, `category_id`, `measure_id`)
VALUES
    ('Баклажан',24,1.2,4.5,0.1,5,1),
    ('Огурец',15,0.8,2.8,0.1,5,1),
    ('Помидор',20,1.2,3.2,1,5,1),
    ('Чеснок',143,6.5,30,0.5,5,1),
    ('Морковь',32,1.3,6.7,0.1,5,1),
    ('Лук Репччатый',47,1.4,10,0.1,5,1),
    ('Баранина',209,15.6,0,16.3,1,1),
    ('Говядина',187,18.9,0,12.4,1,1),
    ('Мясо Гуся',412,15.2,0,39,1,1),
    ('Грудинка Индейки',84,19.2,0,0.7,1,1);
INSERT INTO `recipes`(`description`, `time`, `author_id`)
VALUES ('Пожарьте говядину и добавьте морковки',45,'01');
INSERT INTO `recipe_product`(`recipe_id`, `product_id`, `value`)
VALUES ('01',5,2),
       ('01',8,2);
ALTER TABLE recipes
    ADD COLUMN title varchar(255);
UPDATE recipes
SET title = 'Говядина с морковкой'
WHERE ID = 1;