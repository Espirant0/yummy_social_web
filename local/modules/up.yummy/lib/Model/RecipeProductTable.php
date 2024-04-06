<?php
namespace Up\Yummy\Model;

use Bitrix\Crm\Measure;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\FloatField,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class RecipeProductTable
 *
 * Fields:
 * <ul>
 * <li> recipe_id int optional
 * <li> product_id int optional
 * <li> value double optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class RecipeProductTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_recipe_product';
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
				'RECIPE_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('RECIPE_PRODUCT_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'RECIPE' => new ReferenceField(
				'RECIPE_ID',
				RecipesTable::class, Join::on('this.RECIPE_ID', 'ref.ID')
			),
			new IntegerField(
				'PRODUCT_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('RECIPE_PRODUCT_ENTITY_PRODUCT_ID_FIELD')
				]
			),
			(new ReferenceField(
				'PRODUCT',
				ProductsTable::class, Join::on('this.PRODUCT_ID', 'ref.ID')))
				->configureJoinType('LEFT'),
			new FloatField(
				'VALUE',
				[
					'title' => Loc::getMessage('RECIPE_PRODUCT_ENTITY_VALUE_FIELD')
				]
			),
			new IntegerField(
				'MEASURE_ID',
				[
					'title' => Loc::getMessage('RECIPE_PRODUCT_ENTITY_MEASURE_ID_FIELD')
				]
			),
			'MEASURE' => new ReferenceField(
				'MEASURE_ID',
				MeasuresTable::class, Join::on('this.MEASURE_ID', 'ref.ID')
			),
		];
	}
}
