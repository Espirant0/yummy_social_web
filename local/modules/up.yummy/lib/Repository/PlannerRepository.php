<?php

namespace Up\Yummy\Repository;

use DateTime;
use Bitrix\Main\Type\Date;
use Up\Yummy\Model\CourseTable;
use Up\Yummy\Model\MeasuresTable;
use Up\Yummy\Model\PlannerTable;
use Up\Yummy\Model\RecipeProductTable;
use Up\Yummy\Model\RecipesTable;


class PlannerRepository
{
	public static function addPlan($date, $course, $recipe, $user): void
	{
		$date = new DateTime($date);
		$date = \Bitrix\Main\Type\DateTime::createFromPhp($date);
		PlannerTable::add([
			'RECIPE_ID' => $recipe,
			'DATE' => $date,
			'COURSE_ID' => $course,
			'USER_ID' => $user,
		]);
	}

	public static function deletePlan($date, $course, $user): void
	{
		$date = new DateTime($date);
		$date = \Bitrix\Main\Type\Date::createFromPhp($date);
		PlannerTable::deleteByFilter([
			'=DATE' => new Date($date),
			'=COURSE_ID' => $course,
			'=USER_ID' => $user,
		]);
	}
	public static function isPlanExists($date, $course, $user):bool
	{
		$date = new DateTime($date);
		$date = \Bitrix\Main\Type\Date::createFromPhp($date);
		$plan = PlannerTable::getRow([
			'filter' => [
				'=DATE' => new Date($date),
				'=COURSE_ID' => $course,
				'=USER_ID' => $user,
			],
		]);
		if(isset($plan))
		{
			return true;
		}
		return false;
	}
	public static function getRecipeList(): array
	{
		return RecipesTable::getList([
			'select' => [
				'ID', 'TITLE',
			],
		])->fetchAll();
	}

	public static function getCourses(): array
	{
		return CourseTable::getList([
			'select' => [
				'ID', 'TITLE'
			],
		])->fetchAll();
	}

	public static function getDailyPlan(int $userId, $date): array
	{
		$date = new DateTime($date);
		$date = \Bitrix\Main\Type\DateTime::createFromPhp($date);
		return PlannerTable::getList([
			'select' => [
				'RECIPE_ID',
				'RECIPE_NAME' => 'RECIPE.TITLE',
				'OWNER_ID' => 'USER_ID',
				'COURSE_NAME' => 'COURSE.TITLE',
				'DATE_OF_PLAN' => 'DATE',
			],
			'filter' => [
				'=DATE_OF_PLAN' => new Date($date),
				'=USER_ID' => $userId,
			],
		])->fetchAll();
	}

	public static function getProducts(array $recipes): array
	{
		$productArray = [];
		foreach ($recipes as $recipe)
		{
			$products = RecipeRepository::getRecipeProducts($recipe["RECIPE_ID"]);
			foreach ($products as $product)
			{
				$nproduct[0] = $product['PRODUCT_ID'];
				$nproduct[1] = $product['VALUE'];
				$nproduct[2] = $product['MEASURE_ID'];
				$nproduct[3] = $product['TITLE'];
				$productArray[] = $nproduct;

			}
		}
		$productArray = RecipeRepository::mergeProducts($productArray);
		foreach ($productArray as &$product)
		{
			$product[2] = MeasureRepository::getMeasureName($product[2]);
		}
		return $productArray;
	}

	public static function getPlanForWeek($user, $start): array
	{
		$finish = $start + 604800;
		$start = \Bitrix\Main\Type\DateTime::createFromTimestamp($start);
		$finish = \Bitrix\Main\Type\DateTime::createFromTimestamp($finish);
		$plan = PlannerTable::getList([
			'select' => [
				'RECIPE_ID',
				'RECIPE_NAME' => 'RECIPE.TITLE',
				'OWNER_ID' => 'USER_ID',
				'COURSE_NAME' => 'COURSE.TITLE',
				'DATE_OF_PLAN' => 'DATE',
			],
			'filter' => [
				">DATE_OF_PLAN" => $start,
				"<=DATE_OF_PLAN" => $finish,
				"=OWNER_ID" => $user,
			],
		]);
		return $plan->fetchAll();
	}
}