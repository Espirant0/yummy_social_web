<?php

namespace Up\Yummy\Repository;

use DateTime,
	Bitrix\Main\Type\Date,
	Up\Yummy\Model\CourseTable,
	Up\Yummy\Model\FeaturedTable,
	Up\Yummy\Model\MeasuresTable,
	Up\Yummy\Model\PlannerTable,
	Up\Yummy\Model\RecipesTable,
	Up\Yummy\Service\ValidationService;


class PlannerRepository
{
	public static function addPlan($date, $course, $recipe, $user): void
	{
		$date = new DateTime($date);
		$date = \Bitrix\Main\Type\DateTime::createFromPhp($date);
		if ((RecipesTable::getRowById($recipe) !== null) && (CourseTable::getRowById($course) !== null))
		{
			PlannerTable::add([
				'RECIPE_ID' => $recipe,
				'DATE' => $date,
				'COURSE_ID' => $course,
				'USER_ID' => $user,
			]);
		}
	}

	public static function deletePlan($date, $course, $user): void
	{
		$date = new DateTime($date);
		$date = Date::createFromPhp($date);
		PlannerTable::deleteByFilter([
			'=DATE' => new Date($date),
			'=COURSE_ID' => $course,
			'=USER_ID' => $user,
		]);
	}

	public static function isPlanExists($date, $course, $user): bool
	{
		$date = new DateTime($date);
		$date = Date::createFromPhp($date);
		$plan = PlannerTable::getRow([
			'filter' => [
				'=DATE' => new Date($date),
				'=COURSE_ID' => $course,
				'=USER_ID' => $user,
			],
		]);
		if (isset($plan))
		{
			return true;
		}
		return false;
	}

	public static function getRecipeList($userId = 1): array
	{
		$recipeIds = FeaturedTable::query()->setSelect(['RECIPE_ID'])->setFilter(['=USER_ID' => $userId]);
		$recipes = RecipesTable::query()->setSelect(['ID', 'TITLE'])->whereIn('ID', $recipeIds)->fetchAll();;
		return ($recipes);
	}

	public static function getCourses(): array
	{
		return CourseTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getMeasureName($measureId)
	{
		return MeasuresTable::getByPrimary($measureId)->fetchObject()['TITLE'];
	}

	public static function getDailyPlan(int $userId, $date): array
	{
		$date = new DateTime($date);
		$date = Date::createFromPhp($date);
		$recipeList = PlannerTable::query()->setSelect([
			'RECIPE_ID',
			'RECIPE_NAME' => 'RECIPE.TITLE',
			'OWNER_ID' => 'USER_ID',
			'COURSE_NAME' => 'COURSE.TITLE',
			'DATE_OF_PLAN' => 'DATE',
		])->setFilter([
			'=DATE_OF_PLAN' => new Date($date),
			'=USER_ID' => $userId,
		])->setOrder([
			'COURSE_ID' => 'ASC',
		])->fetchAll();
		return ValidationService::protectPlannerRecipeOutput($recipeList, 'RECIPE_NAME');
	}

	public static function getProducts(array $recipes): array
	{
		$productArray = [];
		foreach ($recipes as $recipe)
		{
			$products = RecipeRepository::getRecipeProducts($recipe['RECIPE_ID']);
			foreach ($products as $product)
			{
				$productArray[] = [
					$product['PRODUCT_ID'],
					$product['VALUE'],
					$product['MEASURE_ID'],
					$product['TITLE'],
				];

			}
		}
		$productArray = RecipeRepository::mergeProducts($productArray);
		foreach ($productArray as &$product)
		{
			$product[2] = self::getMeasureName($product[2]);
		}
		return $productArray;
	}

	public static function getPlanForWeek($userId, $weekStart): array
	{
		$weekEnd = $weekStart + 604800;
		$weekStart = \Bitrix\Main\Type\DateTime::createFromTimestamp($weekStart);
		$weekEnd = \Bitrix\Main\Type\DateTime::createFromTimestamp($weekEnd);
		$plan = PlannerTable::query()->setSelect([
			'RECIPE_ID',
			'RECIPE_NAME' => 'RECIPE.TITLE',
			'OWNER_ID' => 'USER_ID',
			'COURSE_NAME' => 'COURSE.TITLE',
			'DATE_OF_PLAN' => 'DATE',
		])->setFilter([
			">DATE_OF_PLAN" => $weekStart,
			"<=DATE_OF_PLAN" => $weekEnd,
			"=OWNER_ID" => $userId,
		])->fetchAll();
		return ValidationService::protectPlannerRecipeOutput($plan, 'RECIPE_NAME');
	}
}