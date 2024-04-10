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
