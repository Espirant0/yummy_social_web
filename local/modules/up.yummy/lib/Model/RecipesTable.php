<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\FloatField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
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
 * <li> title text optional
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
		return 'up_final_recipes';
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
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('RECIPES_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'TITLE',
				[
					'validation' => [__CLASS__, 'validateTitle'],
					'title' => Loc::getMessage('RECIPES_ENTITY_TITLE_FIELD')
				]
			),
			new TextField(
				'DESCRIPTION',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_DESCRIPTION_FIELD')
				]
			),
			new IntegerField(
				'TIME',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_TIME_FIELD')
				]
			),
			new IntegerField(
				'AUTHOR_ID',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_AUTHOR_ID_FIELD')
				]
			),
			'AUTHOR' => new ReferenceField(
				'AUTHOR_ID',
				UserTable::class, Join::on('this.AUTHOR_ID', 'ref.ID')
			),
			new FloatField(
				'CALORIES',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_CALORIES_FIELD')
				]
			),
			new FloatField(
				'PROTEINS',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_PROTEINS_FIELD')
				]
			),
			new FloatField(
				'CARBS',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_CARBS_FIELD')
				]
			),
			new FloatField(
				'FATS',
				[
					'title' => Loc::getMessage('RECIPES_ENTITY_FATS_FIELD')
				]
			),
			'RECIPE_PRODUCT' => new ReferenceField(
				'RECIPE_ID',
				RecipeProductTable::class, Join::on('this.ID', 'ref.RECIPE_ID')
			),
			new IntegerField(
				'IS_PUBLIC',
				[
					'default' => 0,
					'title' => Loc::getMessage('RECIPES_ENTITY_IS_PUBLIC_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for TITLE field.
	 *
	 * @return array
	 */
	public static function validateTitle()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}