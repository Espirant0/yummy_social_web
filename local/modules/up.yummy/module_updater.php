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



