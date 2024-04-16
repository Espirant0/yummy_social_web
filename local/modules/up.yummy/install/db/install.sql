CREATE TABLE IF NOT EXISTS `up_final_recipes` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255),
  `DESCRIPTION` text,
  `TIME` int,
  `AUTHOR_ID` int,
  `CALORIES` float,
  `PROTEINS` float,
  `CARBS` float,
  `FATS` float
);

CREATE TABLE IF NOT EXISTS`up_final_products` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255),
  `CALORIES` float,
  `PROTEINS` float,
  `CARBS` float,
  `FATS` float,
  `CATEGORY_ID` int,
  `WEIGHT_PER_UNIT` float NULL
);

CREATE TABLE IF NOT EXISTS`up_final_recipe_product` (
  `RECIPE_ID` int,
  `PRODUCT_ID` int,
  `VALUE` float,
  `MEASURE_ID` int
);

CREATE TABLE IF NOT EXISTS `up_final_measures` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255),
  `COEFFICIENT` float NULL
);

CREATE TABLE IF NOT EXISTS `up_final_categories` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255)
);

CREATE TABLE IF NOT EXISTS `up_final_images` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `PATH` varchar(255),
  `RECIPE_ID` int,
  `IS_COVER` tinyint(1)
);

CREATE TABLE IF NOT EXISTS `up_final_planner` (
  `RECIPE_ID` int,
  `USER_ID` int,
  `COURSE_ID` int,
  `DATE` date
);

CREATE TABLE IF NOT EXISTS `up_final_course` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255)
);

CREATE TABLE IF NOT EXISTS `up_final_featured` (
  `USER_ID` int,
  `RECIPE_ID` int
);

CREATE TABLE IF NOT EXISTS `up_final_product_measures` (
  PRODUCT_ID int  ,
  MEASURE_ID int
);

CREATE TABLE IF NOT EXISTS `up_final_likes` (
  `USER_ID` int,
  `RECIPE_ID` int
);
CREATE TABLE IF NOT EXISTS `up_final_instruction` (
   `RECIPE_ID` int,
   `STEP` int,
   `DESCRIPTION` text,
   `IMAGE_ID` int
);

INSERT INTO up_final_categories(TITLE)
VALUES
    ('Мясо'),
    ('Рыба'),
    ('Птица'),
    ('Морепродукты'),
    ('Овощи'),
    ('Фрукты'),
    ('Зелень'),
    ('Грибы'),
    ('Яйца/Яичные продукты'),
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

INSERT INTO up_final_measures(TITLE, COEFFICIENT)
VALUES
('гр', 1),
('кг', 1000),
('чн. лжк.', 5),
('ст. лжк.', 15),
('л', 1000),
('мл', 1),
('шт', NULL),
('По вкусу', NULL);

INSERT INTO `up_final_products` (NAME, CALORIES, PROTEINS, CARBS, FATS, CATEGORY_ID, WEIGHT_PER_UNIT)
VALUES
    ('Баклажан',24,1.2,4.5,0.1,5,200),
    ('Огурец',15,0.8,2.8,0.1,5,120),
    ('Помидор',20,1.2,3.2,1,5,125),
    ('Чеснок',143,6.5,30,0.5,5,40),
    ('Морковь',32,1.3,6.7,0.1,5,100),
    ('Лук Репчатый',47,1.4,10,0.1,5,150),
    ('Баранина',209,15.6,0,16.3,1,NULL),
    ('Говядина',187,18.9,0,12.4,1,NULL),
    ('Мясо Гуся',412,15.2,0,39,1,NULL),
    ('Грудинка Индейки',84,19.2,0,0.7,1,NULL);


INSERT INTO `up_final_recipes` (TITLE, DESCRIPTION, TIME, AUTHOR_ID, CALORIES, PROTEINS, CARBS, FATS)
VALUES ('Говядина с морковкой', 'Пожарьте говядину и добавьте морковки', 45, 1, 0, 0, 0, 0),
       ('ТУТ ЕЩЕ ПРИМЕР РЕЦЕПТА','ПРИМЕР РЕЦЕПТА 1',120, 1, 433, 3, 54, 5),
       ('ТУТ ЕЩЕ ОДИН ПРИМЕР РЕЦЕПТА', 'ПРИМЕР РЕЦЕПТА 2', 120,2,432, 5, 1, 6),
       ('ТУТ ЕЩЕ ВТОРОЙ ПРИМЕР РЕЦЕПТА', 'ПРИМЕР РЕЦЕПТА 3', 120,2,64, 6, 6, 8);


INSERT INTO `up_final_recipe_product` (RECIPE_ID, PRODUCT_ID, VALUE, MEASURE_ID)
VALUES (1 ,5, 200, 1),
       (1, 8, 1.5, 2);

INSERT INTO up_final_product_measures(PRODUCT_ID, MEASURE_ID) VALUES
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
CREATE TABLE IF NOT EXISTS `up_final_daily_recipe`(`ID` int PRIMARY KEY);
alter table up_final_daily_recipe add RECIPE_ID INT default 1;
INSERT INTO `up_final_products`
(`NAME`, `CALORIES`, `PROTEINS`, `CARBS`, `FATS`, `CATEGORY_ID`, `WEIGHT_PER_UNIT`)
VALUES
    ('Яблоко',52,0,14,0.2,6,242),
    ('Груша',57,0,15,0.1,6,150),
    ('Куриная грудка',165,23,15,3.6,1,NULL),
    ('Соль',0,0,0,0.0,17,NULL),
    ('Перец',0,0,0,0.0,17,NULL),
    ('Вода',0,0,0,0.0,19,NULL),
    ('Яблочный сок',42,0.4,9.8,0.4,19,NULL),
    ('Апельсиновый сок',36,0.9,8.1,0.2,19,NULL),
    ('Куриная голень',198,18,14,3.6,1,70);
INSERT INTO `up_final_recipes`
(`TITLE`,
 `DESCRIPTION`,
 `TIME`, `AUTHOR_ID`, `CALORIES`,
 `PROTEINS`, `CARBS`, `FATS`)
VALUES
    ('Куриная грудка с яблоками',
     'Так у нас нет  БД целой курицы обойдёмся этим.
    Посолите и поперчите куриную грудку. Положите её и 3 яблока в духовку и оставьте её на 90 минут при 220С. Оставьте ее на столе на 20 минут и приступайте к еде',
     120,1,287,
     34,56,12),
    ('Свежий шроль',
     'Отличный напиток на лето.
      Возьмите литр яблочного сока и воды. Смешайте их в кувшине и поставьте в холодильник на 40 минут. Подавайте со льдом летним вечером',
     40,2,0,
     0,0,0),
    ('Почти съедобный салат',
     'Порежьте яблоки баклажаны и груши на мелкие кусочки.
      Смешайте их в салатнице. Поперчите и посолите.
      Подавайте вашим пленникам(помните что это нарушение женевской конвенции)',
     10,1,120,
     10,32,10),

    ('Хороший салат',
     'Порежьте морковку, огурцы и помдоры.
    Смешайте их в салатнице и добавьте соль и перец по вкусу',
     10,1,160,
     15,35,10);
INSERT INTO `up_final_recipe_product`
(`RECIPE_ID`, `PRODUCT_ID`, `VALUE`, `MEASURE_ID`)
VALUES
    (5,13,200,1),
    (5,14,1,8),
    (5,15,1,8),
    (5,11,3,7),

    (6,16,1,5),
    (6,17,1,5),

    (7,14,1,8),
    (7,15,1,8),
    (7,1,2,7),
    (7,11,2,7),
    (7,12,2,7),

    (8,14,1,8),
    (8,15,1,8),
    (8,2,2,7),
    (8,3,2,7),
    (8,5,2,7);