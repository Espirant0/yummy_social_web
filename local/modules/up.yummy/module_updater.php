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
