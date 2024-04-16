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
 * Class LikesTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int optional
 * <li> RECIPE_ID int optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class LikesTable extends DataManager
{
	use DeleteByFilterTrait;
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_likes';
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
					'title' => Loc::getMessage('LIKES_ENTITY_USER_ID_FIELD')
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
					'title' => Loc::getMessage('LIKES_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'RECIPE' => new ReferenceField(
				'RECIPE_ID',
				RecipesTable::class, Join::on('this.RECIPE_ID', 'ref.ID')
			),
		];
	}
}