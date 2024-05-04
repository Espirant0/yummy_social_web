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


__yummyMigrate(55, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("CREATE TABLE IF NOT EXISTS `up_final_likes` (
  `USER_ID` int,
  `RECIPE_ID` int
);");
	}
});

__yummyMigrate(56, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("CREATE TABLE IF NOT EXISTS `up_final_instruction` (
   `RECIPE_ID` int,
   `STEP` int,
   `DESCRIPTION` text,
   `IMAGE_ID` int
);");
	}
});

__yummyMigrate(58, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO up_final_product_measures(PRODUCT_ID, MEASURE_ID) VALUES
    (11,1),
    (11,2),
    (11,7),
    (12,1),
    (12,2),
    (12,7),
    (13,1),
    (13,2),
    (14,1),
    (14,8),
    (15,1),
    (15,8),
    (16,5),
    (16,6),
    (17,5),
    (17,6),
    (18,5),
    (18,6),
    (19,1),
    (19,2),
    (19,7);");
	}
});

__yummyMigrate(60, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_course` (`TITLE`) VALUES
('Завтрак'),
('Обед'),
('Ужин');");
	}
});

__yummyMigrate(61, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_planner`(`RECIPE_ID`, `USER_ID`, `COURSE_ID`, `DATE`) VALUES
(1,1,1,'2024-04-26')
");	}
});
__yummyMigrate(62, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_products` (NAME, CALORIES, PROTEINS, CARBS, FATS, CATEGORY_ID, WEIGHT_PER_UNIT)
VALUES
    ('Апельсин',36 ,0.9, 8.1,0.2,6,200),
    ('Банан',96 ,1.5, 21,0.5,6,150 ),
    ('Лимон',16 ,0.9, 3,0.1 ,6,100),
    ('Ананас Консервированный',57 ,0.1, 14,0.1 ,6,NULL),

    ('Арахис',622 ,26.3, 9.9,45.2 ,13,NULL),
    ('Грецкий орех',654 ,15.2 , 7.0 ,65.2 ,13,NULL),
    ('Кешью жареный',572 ,17.5 , 30.5 ,42.2 ,13,NULL),
    ('Курага',215 ,5.2 , 51 ,42.2 ,13,NULL),

    ('Филе судака',105 ,17.1 , 0 ,4.4 ,2,NULL),
    ('Филе леща',105 ,18.5 , 0 ,4.4 ,2,NULL),
    ('Филе сёмги',202 ,22.5 , 0 ,12.5 ,2,NULL),

    ('Королевские креветки',91 ,20.7 , 0 ,0.9 ,4,NULL),

    ('Шампиньоны свежие',27 ,4.3 , 0.1 ,1 ,8,NULL),
    ('Лисички свежие',19 ,1.5 , 1 ,1 ,8,NULL),
    ('Белый гриб',34 ,3.7 , 1.1 ,1.7 ,8,NULL),

    ('Подсолнечное масло',900 ,0 , 0 ,99 ,12,NULL),
    ('Оливковое масло', 898  ,0 , 0 ,99.8 ,12,NULL),

    ('Свекла',42 ,1.5 , 8.8 ,0.1 ,5,250),
    ('Сладкий перец', 27  ,1.3 , 5.3 ,0.1 ,5,200);");	}
});
__yummyMigrate(63, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO up_final_product_measures(PRODUCT_ID, MEASURE_ID) VALUES
(20,1),
(20,2),
(20,7),
(21,1),
(21,2),
(21,7),
(22,1),
(22,2),
(22,7),
(23,1),
(23,2),
(24,1),
(24,2),
(25,1),
(25,2),
(26,1),
(26,2),
(27,1),
(27,2),

(28,1),
(28,2),
(29,1),
(29,2),
(30,1),
(30,2),
(31,1),
(31,2),
(32,1),
(32,2),
(33,1),
(33,2),
(34,1),
(34,2),
(35,6),
(36,6),

(37,1),
(37,2),
(37,7),
(38,1),
(38,2),
(38,7);
");	}
});

__yummyMigrate(64, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_products` (NAME, CALORIES, PROTEINS, CARBS, FATS, CATEGORY_ID, WEIGHT_PER_UNIT)
VALUES
    ('Коровье Молоко 3.5%',54 ,2.9, 3.5,4.8,10,NULL),
    ('Картофель',77 ,2, 16.3,0.4,5,120),
    ('Овсяная крупа',342 ,12.3, 59.5,6.1,14,NULL),
    ('Гречневая крупа',308  ,12.6, 57.1,3.3,14,NULL),
    ('Макароны(Категория А)',345  ,11.5, 67,2.9,16,NULL);
");	}
});
__yummyMigrate(65, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO up_final_product_measures(PRODUCT_ID, MEASURE_ID) VALUES
(39,5),
(39,6),
(40,1),
(40,2),
(40,7),
(41,1),
(41,2),
(42,1),
(42,2),
(43,1),
(43,2),
(35,3),
(36,3),
(35,4),
(36,4);");	}
});
