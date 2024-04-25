<?php
namespace Up\Yummy\Repository;
use Up\Yummy\Model\CourseTable;
use Up\Yummy\Model\MeasuresTable;
use Up\Yummy\Model\PlannerTable;

class PlannerRepository
{
	public static function addPlan()
	{
		return null;
	}
	public static function deletePlan()
	{
		return null;
	}

	public static function getPlan():array
	{
		$plan = PlannerTable::getList([
			'select' => [
				'recipe_name' => 'RECIPE.TITLE',
				'owner_id' => 'USER_ID',
				'course_name' => 'COURSE.TITLE',
				'date_of_plan' => 'DATE',
				],
			'group' => ['course_name'],
		]);

		return $plan->fetchAll();
	}

	public static function getCourses():array
	{
		return CourseTable::getList([
			'select' => [
				'ID', 'TITLE'
			],
		])->fetchAll();
	}

	public static function getDailyPlan(int $userId, $date):array
	{
		$recipes = PlannerTable::getList([
			'select' => [
				'RECIPE_NAME' => 'RECIPE.TITLE',
				'COURSE_NAME' => 'COURSE.TITLE',
				'DATE'
			],
			'filter' => [
				'=USER_ID' => $userId,
			],
		])->fetchAll();
		$dailyRecipes = [];
		foreach ($recipes as $recipe)
		{
			if(strtotime($recipe['DATE']) === strtotime($date))
			{
				$dailyRecipes [] = $recipe;
			}
		}

		$groupedRecipes = array();

		foreach ($dailyRecipes as $recipe) {
			$meal = $recipe["COURSE_NAME"];
			$recipeName = $recipe["RECIPE_NAME"];

			if (!isset($groupedRecipes[$meal])) {
				$groupedRecipes[$meal] = array($recipeName);
			} else {
				if (!in_array($recipeName, $groupedRecipes[$meal])) {
					$groupedRecipes[$meal][] = $recipeName;
				}
			}
		}

		$result = array();

		foreach ($groupedRecipes as $meal => $recipeList) {
			$result[] = array("COURSE_NAME" => $meal, "RECIPE_NAME" => implode(", ", $recipeList));
		}
		return $result;
	}
}