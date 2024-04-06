<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

function __yummyMigrate(int $nextVersion, callable $callback): void
{
	global $DB;
	$moduleId = 'up.yummy';

	if (!ModuleManager::isModuleInstalled($moduleId))
	{
		return;
	}

	$currentVersion = intval(Option::get($moduleId, '~database_schema_version', 0));
	if ($currentVersion < $nextVersion)
	{
		include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_class.php');
		$updater = new \CUpdater();
		$updater->Init('', 'mysql', '', '', $moduleId, 'DB');

		$callback($updater, $DB, 'mysql');
		Option::set($moduleId, '~database_schema_version', $nextVersion);
	}
}


__yummyMigrate(2, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO categories(title)
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
			('Напитки/Соки');");
	}
});
__yummyMigrate(3, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO measures(title)
			VALUES
			('гр'),
			('кг'),
			('чн. лжк.'),
			('ст. лжк.'),
			('л'),
			('мл'),
			('шт'),
			('По вкусу');");
	}
});
__yummyMigrate(4, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `products`(`name`, `calories`, `proteins`, `carbs`, `fats`, `category_id`, `measure_id`) 
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
('Грудинка Индейки',84,19.2,0,0.7,1,1);");
	}
});
__yummyMigrate(5, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("
INSERT INTO `recipes`(`description`, `time`, `author_id`)
VALUES ('Пожарьте говядину и добавьте морковки',45,'01');
");}
});

__yummyMigrate(6, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("
INSERT INTO `recipe_product`(`recipe_id`, `product_id`, `value`) 
VALUES ('01',5,2),
('01',8,2);
");}
});
__yummyMigrate(7, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("
ALTER TABLE recipes
    ADD COLUMN title varchar(255);
");}
});
__yummyMigrate(8, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("
UPDATE recipes
SET title = 'Говядина с морковкой'
WHERE ID = 1;
");}
});
__yummyMigrate(9, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("
CREATE TABLE IF NOT EXISTS `product_measures` (
 PRODUCT_ID int  ,
 MEASURE_ID int
);
");}
});

__yummyMigrate(10, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE products
    ADD COLUMN WEIGHT_PER_UNIT float;");}
});
__yummyMigrate(11, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE products
SET WEIGHT_PER_UNIT = 200
WHERE ID = 1;
");}
});
__yummyMigrate(12, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE products
SET WEIGHT_PER_UNIT = 120
WHERE ID = 2;
");}
});
__yummyMigrate(13, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE products
SET WEIGHT_PER_UNIT = 125
WHERE ID = 3;
");}
});
__yummyMigrate(14, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE products
SET WEIGHT_PER_UNIT = 40
WHERE ID = 4;
");}
});
__yummyMigrate(15, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE products
SET WEIGHT_PER_UNIT = 100
WHERE ID = 5;
");}
});
__yummyMigrate(16, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE products
SET WEIGHT_PER_UNIT = 150
WHERE ID = 6;
");}
});
__yummyMigrate(17, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE products
    DROP COLUMN measure_id;
");}
});
__yummyMigrate(18, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO product_measures(PRODUCT_ID, MEASURE_ID) VALUES 
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
");}
});
__yummyMigrate(19, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE measures
    ADD COLUMN COEFFICIENT float;
");}
});
__yummyMigrate(20, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE recipe_product
    ADD COLUMN MEASURE_ID int;
");}
});
__yummyMigrate(21, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = 1
WHERE ID = 1;
");}
});
__yummyMigrate(22, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT =1000
WHERE ID = 2;
");}
});
__yummyMigrate(23, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT =5
WHERE ID = 3;
");}
});
__yummyMigrate(24, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT =15
WHERE ID = 4;

");}
});
__yummyMigrate(25, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = 1000
WHERE ID = 5;
");}
});
__yummyMigrate(26, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = 1
WHERE ID = 6;
");}
});
__yummyMigrate(27, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = NULL
WHERE ID = 7;
");}
});
__yummyMigrate(28, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = 0
WHERE ID =8;
");}
});
__yummyMigrate(31, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = 0
WHERE ID =8;
");}
});
__yummyMigrate(32, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE measures
SET COEFFICIENT = 0
WHERE ID =8;
");}
});
__yummyMigrate(33, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("UPDATE recipe_product
SET MEASURE_ID = 2
");}
});
__yummyMigrate(34, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE categories
    CHANGE id  ID int  ,
    CHANGE title  TITLE varchar(255);
");}
});
__yummyMigrate(35, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE recipes
    CHANGE id ID int ,
    CHANGE description DESCRIPTION text,
    CHANGE time TIME int,
    CHANGE author_id AUTHOR_ID int,
    CHANGE calories CALORIES float,
    CHANGE proteins PROTEINS float,
    CHANGE carbs CARBS float,
    CHANGE  fats  FATS float;
");}
});
__yummyMigrate(36, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE products
     CHANGE id ID int,
    CHANGE name NAME varchar(255),
     CHANGE calories CALORIES float,
     CHANGE proteins PROTEINS float,
     CHANGE carbs CARBS float,
     CHANGE fats FATS float,
     CHANGE category_id CATEGORY_ID int;
");}
});
__yummyMigrate(37, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE recipe_product
   CHANGE recipe_id RECIPE_ID int,
    CHANGE product_id PRODUCT_ID int,
    CHANGE value VALUE float;
");}
});
__yummyMigrate(38, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE  measures 
      CHANGE id ID int,
    CHANGE title TITLE varchar(255);
");}
});
__yummyMigrate(39, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER TABLE  user_product
    CHANGE user_id USER_ID int,
    CHANGE  product_id PRODUCT_ID int,
    CHANGE  value VALUE float;
");}
});
__yummyMigrate(40, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER  TABLE  images
    CHANGE id ID int ,
    CHANGE path PATH varchar(255),
    CHANGE recipe_id RECIPE_ID int,
    CHANGE is_cover IS_COVER tinyint(1);
");}
});
__yummyMigrate(41, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER  TABLE  planner
    CHANGE recipe_id RECIPE_ID int,
    CHANGE user_id USER_ID int,
    CHANGE course_id COURSE_ID int,
    CHANGE  date DATE date;
");}
});
__yummyMigrate(42, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER  TABLE course
    CHANGE  id ID int ,
    CHANGE title TITLE varchar(255);
");}
});
__yummyMigrate(43, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("ALTER  TABLE featured
    CHANGE user_id USER_ID int,
    CHANGE recipe_id RECIPE_ID int;
");}
});
__yummyMigrate(44, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `recipes`(`ID`,`DESCRIPTION`, `TIME`, `AUTHOR_ID`, `title`)
VALUES 
('ТУТ ЕЩЕ ПРИМЕР РЕЦЕПТА',120,1,'ПРИМЕР РЕЦЕПТА 1'),
('ТУТ ЕЩЕ ОДИН ПРИМЕР РЕЦЕПТА',120,1,'ПРИМЕР РЕЦЕПТА 2'),
('ТУТ ЕЩЕ ВТОРОЙ ПРИМЕР РЕЦЕПТА',120,1,'ПРИМЕР РЕЦЕПТА 3');
");}
});