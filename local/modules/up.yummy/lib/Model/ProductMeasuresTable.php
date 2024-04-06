<?php
namespace Up\Yummy\Model;

use Bitrix\Crm\Volume\Product;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class ProductMeasuresTable
 *
 * Fields:
 * <ul>
 * <li> PRODUCT_ID int optional
 * <li> MEASURE_ID int optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class ProductMeasuresTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_product_measures';
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
				'PRODUCT_ID',
				[
					'title' => Loc::getMessage('PRODUCT_MEASURES_ENTITY_PRODUCT_ID_FIELD')
				]
			),
			'PRODUCT' => new ReferenceField(
				'PRODUCT_ID',
				ProductsTable::class, Join::on('this.PRODUCT_ID', 'ref.ID')
			),
			new IntegerField(
				'MEASURE_ID',
				[
					'title' => Loc::getMessage('PRODUCT_MEASURES_ENTITY_MEASURE_ID_FIELD')
				]
			),
			'MEASURE' => new ReferenceField(
				'MEASURE_ID',
				MeasuresTable::class, Join::on('this.MEASURE_ID', 'ref.ID')
			),
		];
	}
}
