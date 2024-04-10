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
		CAgent::AddAgent('Up\Yummy\Agents\RecipeAgent::getDailyRecipe();', "up.yummy", "N", 86400);
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

	public function installLinks(array $itemData): void
	{
		$siteId = 's1';
		$adminOption = Option::get('intranet', 'left_menu_items_to_all_' . $siteId, '', $siteId);

		if (!empty($adminOption))
		{
			$adminOption = unserialize($adminOption, ['allowed_classes' => false]);
				foreach ($adminOption as $item)
				{
					if ($item['ID'] == $itemData['ID'])
						break;
				}
			$adminOption[] = $itemData;
		}
		else
		{
			$adminOption = array($itemData);
		}

		Option::set('intranet', 'left_menu_items_to_all_' . $siteId, serialize($adminOption), false, $siteId);
	}

	public function uninstallLinks(array $itemData):void
	{
		$siteId = 's1';
		foreach (['left_menu_items_to_all_' . $siteId, 'left_menu_items_marketplace_' . $siteId] as $optionName)
		{
			if (($adminOption = Option::get('intranet', $optionName, '', $siteId))
				&& !empty($adminOption)
				&& ($adminOption = unserialize($adminOption, ['allowed_classes' => false]))
			)
			{
				foreach ($adminOption as $key => $item)
				{
					if ($item['ID'] == $itemData['ID'])
					{
						unset($adminOption[$key]);
						if (empty($adminOption))
						{
							\COption::RemoveOption('intranet', $optionName);
						}
						else
						{
							Option::set('intranet', $optionName, serialize($adminOption), $siteId);
						}
						break 2;
					}
				}
			}
		}
	}

	public function doInstall(): void
	{
		global $USER, $APPLICATION;

		if (!$USER->isAdmin())
		{
			return;
		}
		$links = array(
			array(
				'TEXT' => 'Планировщик',
				'LINK' => '/planner/',
				'ID' => 'plannerId',
				'NEW_PAGE' => 'Y',
			),
			array(
				'TEXT' => 'Детальная',
				'LINK' => '/detail/1/',
				'ID' => 'detailId',
				'NEW_PAGE' => 'Y',
			),
		);

		$this->installDB();
		$this->installFiles();
		$this->installEvents();

		foreach ($links as $link)
		{
			$this->installLinks($link);
		}
		$APPLICATION->IncludeAdminFile(
			Loc::getMessage('UP_YUMMY_INSTALL_TITLE'),
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/step.php'
		);
	}

	public function doUninstall(): void
	{
		global $USER, $APPLICATION, $step;

		$links = array(
			array(
				'TEXT' => 'Добавить рецепт',
				'LINK' => '/create/',
				'ID' => 'newCreateId',
				'NEW_PAGE' => 'Y',
			),
			array(
				'TEXT' => 'Планировщик',
				'LINK' => '/planner/',
				'ID' => 'plannerId',
				'NEW_PAGE' => 'Y',
			),
			array(
				'TEXT' => 'Детальная',
				'LINK' => '/detail/1/',
				'ID' => 'detailId',
				'NEW_PAGE' => 'Y',
			),
		);

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
			foreach ($links as $link)
			{
				$this->uninstallLinks($link);
			}

			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_YUMMY_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep2.php'
			);
		}
	}
}
