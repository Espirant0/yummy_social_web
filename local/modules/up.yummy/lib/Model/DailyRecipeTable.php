<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class DailyRecipeTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * </ul>
 *
 * @package Bitrix\Final
 **/

class DailyRecipeTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_daily_recipe';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			(new IntegerField('ID',
				[]
			))->configureTitle(Loc::getMessage('DAILY_RECIPE_ENTITY_ID_FIELD'))
				->configurePrimary(true),
			(new IntegerField('RECIPE_ID',
				[]
			))->configureTitle(Loc::getMessage('DAILY_RECIPE_ENTITY_RECIPE_ID_FIELD'))
				->configureDefaultValue(1),
		];
	}
}