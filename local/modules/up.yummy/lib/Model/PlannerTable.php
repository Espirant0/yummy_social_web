<?php
namespace Up\Yummy\Model;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\DateField,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Data\Internal\DeleteByFilterTrait;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

/**
 * Class PlannerTable
 *
 * Fields:
 * <ul>
 * <li> recipe_id int optional
 * <li> user_id int optional
 * <li> course_id int optional
 * <li> date date optional
 * </ul>
 *
 * @package Up\Yummy\Model
 **/

class PlannerTable extends DataManager
{
	use DeleteByFilterTrait;
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_final_planner';
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
					'title' => Loc::getMessage('PLANNER_ENTITY_RECIPE_ID_FIELD')
				]
			),
			'RECIPE' => new ReferenceField(
				'RECIPE_ID',
				RecipesTable::class, Join::on('this.RECIPE_ID', 'ref.ID')
			),
			new IntegerField(
				'USER_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('PLANNER_ENTITY_USER_ID_FIELD')
				]
			),
			'USER' => new ReferenceField(
				'USER_ID',
				UserTable::class, Join::on('this.USER_ID', 'ref.ID')
			),
			new IntegerField(
				'COURSE_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('PLANNER_ENTITY_COURSE_ID_FIELD')
				]
			),
			'COURSE' => new ReferenceField(
				'COURSE_ID',
				CourseTable::class, Join::on('this.COURSE_ID', 'ref.ID')
			),
			new DateField(
				'DATE',
				[
					'primary' => true,
					'title' => Loc::getMessage('PLANNER_ENTITY_DATE_FIELD')
				]
			),
		];
	}
}