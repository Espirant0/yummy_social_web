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

