<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

class up_yummy extends CModule
{
	public $MODULE_ID = 'up.yummy';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;

	public function __construct()
	{
		$arModuleVersion = [];
		include(__DIR__ . '/version.php');

		if (is_array($arModuleVersion) && $arModuleVersion['VERSION'] && $arModuleVersion['VERSION_DATE'])
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->MODULE_NAME = Loc::getMessage('UP_YUMMY_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('UP_YUMMY_MODULE_DESCRIPTION');
	}

	public function installDB(): void
	{
		global $DB;
		$nextStart = new DateTime('tomorrow');
		$nextStart = $nextStart->format('d.m.Y H:i:s');
		CAgent::AddAgent(
			'Up\Yummy\Agents\RecipeAgent::getDailyRecipe();',
			"up.yummy",
			"N",
			86400,
			"$nextStart",
			"Y",
			"$nextStart"
		);
		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/db/install.sql');

		ModuleManager::registerModule($this->MODULE_ID);
	}

	public function uninstallDB($arParams = []): void
	{
		global $DB;

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/db/uninstall.sql');

		ModuleManager::unRegisterModule($this->MODULE_ID);
	}

	public function installFiles(): void
	{
		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/components',
			$_SERVER['DOCUMENT_ROOT'] . '/local/components',
			true,
			true
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/templates',
			$_SERVER['DOCUMENT_ROOT'] . '/local/templates/yummy',
			true,
			true
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/routes',
			$_SERVER['DOCUMENT_ROOT'] . '/local/routes',
			true,
			true
		);
	}

	public function uninstallFiles(): void
	{
		DeleteDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/components',
			$_SERVER['DOCUMENT_ROOT'] . '/local/components/up'

		);

		DeleteDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/templates',
			$_SERVER['DOCUMENT_ROOT'] . '/local/templates/yummy',
		);

		DeleteDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.yummy/install/routes',
			$_SERVER['DOCUMENT_ROOT'] . '/local/routes',
		);
	}

	public function doInstall(): void
	{
		global $USER, $APPLICATION;

		if (!$USER->isAdmin())
		{
			return;
		}

		$this->installDB();
		$this->installFiles();
		$this->installEvents();

		$APPLICATION->IncludeAdminFile(
			Loc::getMessage('UP_YUMMY_INSTALL_TITLE'),
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/step.php'
		);
	}

	public function doUninstall(): void
	{
		global $USER, $APPLICATION, $step;

		if (!$USER->isAdmin())
		{
			return;
		}

		$step = (int)$step;
		if ($step < 2)
		{
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_YUMMY_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep1.php'
			);
		}
		elseif ($step === 2)
		{
			$this->uninstallDB();
			$this->uninstallFiles();
			$this->uninstallEvents();

			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_YUMMY_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep2.php'
			);
		}
	}
}
