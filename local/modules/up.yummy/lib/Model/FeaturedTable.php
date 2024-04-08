<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Data\Internal\DeleteByFilterTrait;
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
	use DeleteByFilterTrait;
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_featured';
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
				'USER_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('FEATURED_ENTITY_USER_ID_FIELD')
				]
			),
			'USER' => new ReferenceField(
				'USER_ID',
				UserTable::class, Join::on('this.USER_ID', 'ref.ID')
			),
			new IntegerField(
				'RECIPE_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('FEATURED_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'RECIPE' => new ReferenceField(
				'RECIPE_ID',
				RecipesTable::class, Join::on('this.RECIPE_ID', 'ref.ID')
			),
		];
	}
}