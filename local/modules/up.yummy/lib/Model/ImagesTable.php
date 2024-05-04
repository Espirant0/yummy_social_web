<?php

namespace Up\Yummy\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator,
	Bitrix\Main\ORM\Data\Internal\DeleteByFilterTrait;

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
	use DeleteByFilterTrait;

	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_images';
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
					'title' => Loc::getMessage('IMAGES_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'PATH',
				[
					'validation' => [__CLASS__, 'validatePath'],
					'title' => Loc::getMessage('IMAGES_ENTITY_PATH_FIELD')
				]
			),
			new IntegerField(
				'RECIPE_ID',
				[
					'title' => Loc::getMessage('IMAGES_ENTITY_RECIPE_ID_FIELD')
				]
			),
			new IntegerField(
				'IS_COVER',
				[
					'title' => Loc::getMessage('IMAGES_ENTITY_IS_COVER_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for PATH field.
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
