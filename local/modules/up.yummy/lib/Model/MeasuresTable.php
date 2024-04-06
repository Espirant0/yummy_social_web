<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\FloatField;

Loc::loadMessages(__FILE__);

/**
 * Class MeasuresTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> title string(255) optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class MeasuresTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_measures';
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
					'title' => Loc::getMessage('MEASURES_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'TITLE',
				[
					'validation' => [__CLASS__, 'validateTitle'],
					'title' => Loc::getMessage('MEASURES_ENTITY_TITLE_FIELD')
				]
			),
			new FloatField(
				'COEFFICIENT',
				[
					'title' => Loc::getMessage('MEASURES_ENTITY_COEFFICIENT_FIELD')
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