<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class CourseTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> title string(255) optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class CourseTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'course';
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
				'title',
				[
					'validation' => [__CLASS__, 'validateTitle'],
					'title' => Loc::getMessage('_ENTITY_TITLE_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for title field.
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