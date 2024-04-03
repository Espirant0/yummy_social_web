<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

/**
 * Class FeaturedTable
 *
 * Fields:
 * <ul>
 * <li> user_id int optional
 * <li> recipe_id int optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class FeaturedTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'featured';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'user_id',
				[
					'title' => Loc::getMessage('_ENTITY_USER_ID_FIELD')
				]
			),
			'user' => new ReferenceField(
				'user_id',
				UserTable::class, Join::on('this.user_id', 'ref.ID')
			),
			new IntegerField(
				'recipe_id',
				[
					'title' => Loc::getMessage('_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'recipe' => new ReferenceField(
				'recipe_id',
				RecipesTable::class, Join::on('this.recipe_id', 'ref.ID')
			),
		];
	}
}