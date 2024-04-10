<?php

use Bitrix\Main\Application;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Request;

function request(): Request
{
	return Application::getInstance()->getContext()->getRequest();
}

function db(): Connection
{
	return Application::getConnection();
}

if (file_exists(__DIR__ . '/module_updater.php'))
{
	include (__DIR__ . '/module_updater.php');
}
function getDailyRecipe()
{
//	$number = random_int(1, \Up\Yummy\Model\RecipesTable::getCount());
//	while (!\Up\Yummy\Model\RecipesTable::getByPrimary($number))
//	{
//		$number = random_int(1, \Up\Yummy\Model\RecipesTable::getCount());
//	}
//	echo($number);
	mail('zadira420@gmail.com', 'Агент', 'Агент');
	global $ADK;
	$ADK=random_int(1,1000);
	return "getDailyRecipe();";
}
