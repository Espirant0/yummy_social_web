<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Data\Internal\DeleteByFilterTrait;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class InstructionTable
 *
 * Fields:
 * <ul>
 * <li> RECIPE_ID int optional
 * <li> STEP int optional
 * <li> DESCRIPTION text optional
 * <li> IMAGE_ID int optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class InstructionTable extends DataManager
{
	use DeleteByFilterTrait;
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_instruction';
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
					'title' => Loc::getMessage('INSTRUCTION_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'RECIPE' => new ReferenceField(
			'RECIPE_ID',
			RecipesTable::class, Join::on('this.RECIPE_ID', 'ref.ID')
			),
			new IntegerField(
				'STEP',
				[
					'primary' => true,
					'title' => Loc::getMessage('INSTRUCTION_ENTITY_STEP_FIELD')
				]
			),
			new TextField(
				'DESCRIPTION',
				[
					'title' => Loc::getMessage('INSTRUCTION_ENTITY_DESCRIPTION_FIELD')
				]
			),
			new IntegerField(
				'IMAGE_ID',
				[
					'title' => Loc::getMessage('INSTRUCTION_ENTITY_IMAGE_ID_FIELD')
				]
			),
		];
	}
}