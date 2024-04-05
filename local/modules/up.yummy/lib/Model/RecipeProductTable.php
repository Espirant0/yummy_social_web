<?php
namespace Up\Yummy\Model;

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
		return 'recipe_product';
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
				'recipe_id',
				[
					'primary' => true,
					'title' => Loc::getMessage('PRODUCT_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'recipe' => new ReferenceField(
				'recipe_id',
				RecipesTable::class, Join::on('this.recipe_id', 'ref.ID')
			),
			new IntegerField(
				'product_id',
				[
					'primary' => true,
					'title' => Loc::getMessage('PRODUCT_ENTITY_PRODUCT_ID_FIELD')
				]
			),
			'product' => new ReferenceField(
				'product_id',
				ProductsTable::class, Join::on('this.product_id', 'ref.ID')
			),
			new FloatField(
				'value',
				[
					'title' => Loc::getMessage('PRODUCT_ENTITY_VALUE_FIELD')
				]
			),
		];
	}
}