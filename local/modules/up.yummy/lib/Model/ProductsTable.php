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
		return 'up_final_products';
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
					'title' => Loc::getMessage('PRODUCTS_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'NAME',
				[
					'validation' => [__CLASS__, 'validateName'],
					'title' => Loc::getMessage('PRODUCTS_ENTITY_NAME_FIELD')
				]
			),
			new FloatField(
				'CALORIES',
				[
					'title' => Loc::getMessage('PRODUCTS_ENTITY_CALORIES_FIELD')
				]
			),
			new FloatField(
				'PROTEINS',
				[
					'title' => Loc::getMessage('PRODUCTS_ENTITY_PROTEINS_FIELD')
				]
			),
			new FloatField(
				'CARBS',
				[
					'title' => Loc::getMessage('PRODUCTS_ENTITY_CARBS_FIELD')
				]
			),
			new FloatField(
				'FATS',
				[
					'title' => Loc::getMessage('PRODUCTS_ENTITY_FATS_FIELD')
				]
			),
			new IntegerField(
				'CATEGORY_ID',
				[
					'title' => Loc::getMessage('PRODUCTS_ENTITY_CATEGORY_ID_FIELD')
				]
			),
			'CATEGORY' => new ReferenceField(
				'CATEGORY_ID',
				CategoriesTable::class, Join::on('this.CATEGORY_ID', 'ref.ID')
			),
			new FloatField(
				'WEIGHT_PER_UNIT',
				[
					'title' => Loc::getMessage('PRODUCTS_ENTITY_WEIGHT_PER_UNIT_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for NAME field.
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