<?php
namespace Up\Yummy\Model;

use Bitrix\Crm\Volume\Product;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\FloatField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class ProductsTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> name string(255) optional
 * <li> calories double optional
 * <li> proteins double optional
 * <li> carbs double optional
 * <li> fats double optional
 * <li> category_id int optional
 * <li> measure_id int optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class ProductsTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'products';
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
			new StringField(
				'name',
				[
					'validation' => [__CLASS__, 'validateName'],
					'title' => Loc::getMessage('_ENTITY_NAME_FIELD')
				]
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
			new IntegerField(
				'category_id',
				[
					'title' => Loc::getMessage('_ENTITY_CATEGORY_ID_FIELD')
				]
			),
			'category' => new ReferenceField(
				'category_id',
				CategoriesTable::class, Join::on('this.category_id', 'ref.ID')
			),
			new IntegerField(
				'measure_id',
				[
					'title' => Loc::getMessage('_ENTITY_MEASURE_ID_FIELD')
				]
			),
			'measure' => new ReferenceField(
				'measure_id',
				MeasuresTable::class, Join::on('this.measure_id', 'ref.ID')
			),
		];
	}

	/**
	 * Returns validators for name field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}