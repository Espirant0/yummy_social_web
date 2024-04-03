<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class ImagesTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> path string(255) optional
 * <li> recipe_id int optional
 * <li> is_cover int optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class ImagesTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'images';
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
				'path',
				[
					'validation' => [__CLASS__, 'validatePath'],
					'title' => Loc::getMessage('_ENTITY_PATH_FIELD')
				]
			),
			new IntegerField(
				'recipe_id',
				[
					'title' => Loc::getMessage('_ENTITY_RECIPE_ID_FIELD')
				]
			),
			new IntegerField(
				'is_cover',
				[
					'title' => Loc::getMessage('_ENTITY_IS_COVER_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for path field.
	 *
	 * @return array
	 */
	public static function validatePath()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}
