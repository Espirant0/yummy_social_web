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


__yummyMigrate(45, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("alter table up_final_recipes
    add IS_PUBLIC TINYINT default 0 not null;");
	}
});
__yummyMigrate(48, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("CREATE TABLE IF NOT EXISTS `up_final_daily_recipe`(`ID` int PRIMARY KEY);");
	}
});
__yummyMigrate(50, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO  up_final_daily_recipe(ID) VALUES(1);");
	}
});
__yummyMigrate(51, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("alter table up_final_daily_recipe add RECIPE_ID INT default 1;");
	}
});
__yummyMigrate(52, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_products`
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
('Куриная голень',198,18,14,3.6,1,70);");
	}
});
__yummyMigrate(53, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_recipes`
(`TITLE`, 
 `DESCRIPTION`, 
 `TIME`, `AUTHOR_ID`, `CALORIES`,
 `PROTEINS`, `CARBS`, `FATS`, `IS_PUBLIC`) 
VALUES 
('Куриная грудка с яблоками',
 'Так у нас нет  БД целой курицы обойдёмся этим.
Посолите и поперчите куриную грудку. Положите её и 3 яблока в духовку и оставьте её на 90 минут при 220С. Оставьте ее на столе на 20 минут и приступайте к еде',
 120,1,287,
 34,56,12,1),
 ('Свежий шроль',
 'Отличный напиток на лето.
  Возьмите литр яблочного сока и воды. Смешайте их в кувшине и поставьте в холодильник на 40 минут. Подавайте со льдом летним вечером',
 40,2,0,
 0,0,0,1),
 ('Почти съедобный салат',
 'Порежьте яблоки баклажаны и груши на мелкие кусочки.
  Смешайте их в салатнице. Поперчите и посолите.
  Подавайте вашим пленникам(помните что это нарушение женевской конвенции)',
 10,1,120,
 10,32,10,1),
 
  ('Хороший салат',
 'Порежьте морковку, огурцы и помдоры.
Смешайте их в салатнице и добавьте соль и перец по вкусу',
 10,1,160,
 15,35,10,1);");
	}
});
__yummyMigrate(54, function ($updater, $DB) {
	if ($updater->CanUpdateDatabase())
	{
		$DB->query("INSERT INTO `up_final_recipe_product`
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
(8,5,2,7);");
	}
});