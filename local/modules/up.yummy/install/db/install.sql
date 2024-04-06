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

CREATE TABLE IF NOT EXISTS `product_measures` (
 PRODUCT_ID int  ,
 MEASURE_ID int
);
ALTER TABLE products
    ADD COLUMN WEIGHT_PER_UNIT float;


UPDATE products
SET WEIGHT_PER_UNIT = 200
WHERE ID = 1;

UPDATE products
SET WEIGHT_PER_UNIT = 120
WHERE ID = 2;
UPDATE products
SET WEIGHT_PER_UNIT =125
WHERE ID = 3;
UPDATE products
SET WEIGHT_PER_UNIT =40
WHERE ID = 4;
UPDATE products
SET WEIGHT_PER_UNIT = 100
WHERE ID = 5;
UPDATE products
SET WEIGHT_PER_UNIT = 150
WHERE ID = 6;

ALTER TABLE products
    DROP COLUMN measure_id;
INSERT INTO product_measures(PRODUCT_ID, MEASURE_ID) VALUES
(1,1),
(1,2),
(1,7),
(2,1),
(2,2),
(2,7),
(3,1),
(3,2),
(3,7),
(4,1),
(4,2),
(4,7),
(4,8),
(5,1),
(5,2),
(5,7),
(6,1),
(6,2),
(6,7),
(7,1),
(7,2),
(8,1),
(8,2),
(9,1),
(9,2),
(10,1),
(10,2);

ALTER TABLE measures
    ADD COLUMN COEFFICIENT float;
ALTER TABLE recipe_product
    ADD COLUMN MEASURE_ID int;

UPDATE measures
SET COEFFICIENT = 1
WHERE ID = 1;

UPDATE measures
SET COEFFICIENT =1000
WHERE ID = 2;

UPDATE measures
SET COEFFICIENT =5
WHERE ID = 3;
UPDATE measures
SET COEFFICIENT =15
WHERE ID = 4;

UPDATE measures
SET COEFFICIENT = 1000
WHERE ID = 5;

UPDATE measures
SET COEFFICIENT = 1
WHERE ID = 6;

UPDATE measures
SET COEFFICIENT = NULL
WHERE ID = 7;

UPDATE measures
SET COEFFICIENT = 0
WHERE ID =8;

UPDATE recipe_product
SET MEASURE_ID = 2;

ALTER TABLE categories
    CHANGE id  ID int  ,
    CHANGE title  TITLE varchar(255);
ALTER TABLE recipes
    CHANGE id ID int ,
    CHANGE description DESCRIPTION text,
    CHANGE time TIME int,
    CHANGE author_id AUTHOR_ID int,
    CHANGE calories CALORIES float,
    CHANGE proteins PROTEINS float,
    CHANGE carbs CARBS float,
    CHANGE  fats  FATS float;
ALTER TABLE products
     CHANGE id ID int,
    CHANGE name NAME varchar(255),
     CHANGE calories CALORIES float,
     CHANGE proteins PROTEINS float,
     CHANGE carbs CARBS float,
     CHANGE fats FATS float,
     CHANGE category_id CATEGORY_ID int;
ALTER TABLE recipe_product
   CHANGE recipe_id RECIPE_ID int,
    CHANGE product_id PRODUCT_ID int,
    CHANGE value VALUE float;
ALTER TABLE  measures
      CHANGE id ID int,
    CHANGE title TITLE varchar(255);


ALTER TABLE  user_product
    CHANGE user_id USER_ID int,
    CHANGE  product_id PRODUCT_ID int,
    CHANGE  value VALUE float;

ALTER  TABLE  images
    CHANGE id ID int ,
    CHANGE path PATH varchar(255),
    CHANGE recipe_id RECIPE_ID int,
    CHANGE is_cover IS_COVER tinyint(1);

ALTER  TABLE  planner
    CHANGE recipe_id RECIPE_ID int,
    CHANGE user_id USER_ID int,
    CHANGE course_id COURSE_ID int,
    CHANGE  date DATE date;

ALTER  TABLE course
    CHANGE  id ID int ,
    CHANGE title TITLE varchar(255);

ALTER  TABLE featured
    CHANGE user_id USER_ID int,
    CHANGE recipe_id RECIPE_ID int;