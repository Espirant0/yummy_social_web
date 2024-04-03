<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\FloatField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

/**
 * Class RecipesTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> description text optional
 * <li> time int optional
 * <li> author_id int optional
 * <li> calories double optional
 * <li> proteins double optional
 * <li> carbs double optional
 * <li> fats double optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class RecipesTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'recipes';
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
				'id',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('_ENTITY_ID_FIELD')
				]
			),
			new TextField(
				'description',
				[
					'title' => Loc::getMessage('_ENTITY_DESCRIPTION_FIELD')
				]
			),
			new IntegerField(
				'time',
				[
					'title' => Loc::getMessage('_ENTITY_TIME_FIELD')
				]
			),
			new IntegerField(
				'author_id',
				[
					'title' => Loc::getMessage('_ENTITY_AUTHOR_ID_FIELD')
				]
			),
			'author' => new ReferenceField(
				'author_id',
				UserTable::class, Join::on('this.author_id', 'ref.ID')
			),
			new FloatField(
				'calories',
				[
					'title' => Loc::getMessage('_ENTITY_CALORIES_FIELD')
				]
			),
			new FloatField(
				'proteins',
				[
					'title' => Loc::getMessage('_ENTITY_PROTEINS_FIELD')
				]
			),
			new FloatField(
				'carbs',
				[
					'title' => Loc::getMessage('_ENTITY_CARBS_FIELD')
				]
			),
			new FloatField(
				'fats',
				[
					'title' => Loc::getMessage('_ENTITY_FATS_FIELD')
				]
			),
		];
	}
}