<?php

namespace Up\Yummy\Repository;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ORM\Query\Query;
use Up\Yummy\Model\FeaturedTable;
use Up\Yummy\Model\ProductsTable;
use Up\Yummy\Model\RecipeProductTable;
use Up\Yummy\Model\RecipesTable;

class RecipeRepository
{
	public static function addRecipe()
	{
		return null;
	}

	public static function showRecipeDetail(int $id)
	{
		$recipe = RecipesTable::query()->setSelect(['*'])->where("ID", $id)->fetchObject();
		return $recipe;
	}

	public static function getRecipeProducts(int $id)
	{
		$products = RecipeProductTable::getList([
			'select' =>
				['*', 'TITLE' => 'PRODUCT.NAME', 'MEASURE_NAME' => 'MEASURE.TITLE'],
			'filter' =>
				['=RECIPE_ID' => $id]
		]);
		return $products->fetchAll();
	}

	public static function getProducts()
	{
		$products = ProductsTable::getList([
			'select' =>
				['NAME'],
		]);
		return $products->fetchAll();
	}

	public static function deleteRecipe(int $id): void
	{
		while ($id === Option::get("up.yummy", "dailyRecipe", 1))
		{
			$max = RecipesTable::query()
				->addSelect(Query::expr()->max("ID"), 'MAX')
				->exec()->fetch();
			$recipeId = random_int(1, $max['MAX']);
			$ID = RecipesTable::getByPrimary($recipeId)->fetch()['ID'];
			Option::set("up.yummy", "dailyRecipeId", $ID);
		}
		RecipeProductTable::deleteByFilter(['=RECIPE_ID' => $id]);
		RecipesTable::getByPrimary($id)->fetchObject()->delete();
	}

	public static function addRecipeToFeatured(int $authorId, int $recipeId): void
	{
		FeaturedTable::add([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $authorId,
		]);
	}

	public static function deleteRecipeFromFeatured(int $userId, int $recipeId): void
	{
		FeaturedTable::deleteByFilter([
			'=RECIPE_ID' => $recipeId,
			'=USER_ID' => $userId,
		]);
	}

	public static function validateRecipeAuthor(int $authorId, int $recipeId): bool
	{
		$recipe = RecipesTable::getByPrimary($recipeId)->fetchObject();
		if ($recipe['AUTHOR_ID'] === $authorId)
		{
			return true;
		}
		return false;
	}

	public static function isRecipeInFeatured(int $userId, int $recipeId): bool
	{
		$featuredRow = FeaturedTable::getByPrimary([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $userId,])->fetchObject();
		if ($featuredRow !== null)
		{
			return true;
		}
		return false;
	}

	public static function updateRecipe()
	{
		return null;
	}

	public static function getRecipeFeed(int $page, array $filter)
	{
		$recipes = RecipesTable::query()->setSelect(['*'])->setOffset(3 * ($page - 1))->setLimit(4)->setFilter($filter);
		return $recipes->fetchAll();
	}

	public static function getRecipeStats(int $recipeid)
	{
		$stats = ['CALORIES' => 0, 'PROTEINS' => 0, 'CARBS' => 0, 'FATS' => 0];
		$products = RecipeProductTable::getList([
			'select' =>
				['*', 'TITLE' => 'PRODUCT.NAME',
					'MEASURE_NAME' => 'MEASURE.TITLE',
					'COEFFICIENT' => 'MEASURE.COEFFICIENT',
					'CALORIES' => 'PRODUCT.CALORIES',
					'PROTEINS' => 'PRODUCT.PROTEINS',
					'CARBS' => 'PRODUCT.CARBS',
					'FATS' => 'PRODUCT.FATS'],
			'filter' =>
				['=RECIPE_ID' => $recipeid]
		])->fetchAll();
		foreach ($products as $product)
		{
			$stats['CALORIES'] += $product['CALORIES'] * $product['COEFFICIENT'] * $product['VALUE'] / 100;
			$stats['PROTEINS'] += $product['PROTEINS'] * $product['COEFFICIENT'] * $product['VALUE'] / 100;
			$stats['FATS'] += $product['FATS'] * $product['COEFFICIENT'] * $product['VALUE'] / 100;
			$stats['CARBS'] += $product['CARBS'] * $product['COEFFICIENT'] * $product['VALUE'] / 100;
		}
		var_dump($stats);


	}

	public static function getDailyRecipeTitle(): string
	{
		$dailyRecipeId = Option::get("up.yummy", "dailyRecipe", 1);
		$recipe = RecipesTable::getByPrimary($dailyRecipeId)->fetch()['TITLE'];
		return $recipe;
	}

}