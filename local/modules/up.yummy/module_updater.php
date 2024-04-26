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