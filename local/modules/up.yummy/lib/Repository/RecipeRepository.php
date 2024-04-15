<?php

namespace Up\Yummy\Repository;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ORM\Query\Query;
use Up\Yummy\Model\FeaturedTable;
use Up\Yummy\Model\ProductsTable;
use Up\Yummy\Model\RecipeProductTable;
use Up\Yummy\Model\RecipesTable;
use Up\Yummy\Service\ValidationService;

class RecipeRepository
{
	public static function addRecipe($title, $description, $time, $user)
	{
		$recipeId = RecipesTable::add(['TITLE' => $title, 'DESCRIPTION' => $description, 'TIME' => $time, 'AUTHOR_ID' => $user]);
		return $recipeId->getId();
	}

	public static function showRecipeDetail(int $id)
	{
		$recipe = RecipesTable::query()->setSelect(['*'])->where("ID", $id)->fetch();
		$recipe['IMAGE'] = ImageRepository::getRecipeCover($recipe['ID']);
		$recipe = ValidationService::protectRecipeOutput($recipe);
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
		if ($recipe['AUTHOR_ID'] == $authorId)
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
		global $USER;
		$userId = $USER->GetID();
		$recipes = RecipesTable::query()->setSelect(['*'])->setOffset(3 * ($page - 1))->setLimit(4);

		if (isset($filter['FEATURED']))
		{
			$recipeIds = FeaturedTable::query()->setSelect(['RECIPE_ID'])->setFilter(['=USER_ID' => $userId]);
			if ($filter['FEATURED'] === 'Y')
			{
				$recipes->whereIn('ID', $recipeIds);
			}
			else
			{
				$recipes->whereNotIn('ID', $recipeIds);
			}
		}

		if (isset($filter['TITLE']) or isset($filter['FIND']))
		{
			if ($filter['TITLE'] !== '')
			{
				$recipes->addFilter('%=TITLE', '%' . $filter['TITLE'] . '%');
			}
			if ($filter['FIND'] !== '')
			{
				$recipes->addFilter('%=TITLE', '%' . $filter['FIND'] . '%');
			}
		}

		if (isset($filter['TIME_from']) or isset($filter['TIME_to']))
		{
			if ($filter['TIME_from'] === '')
			{
				$recipes->addFilter('<TIME', $filter['TIME_to']);
			}
			elseif ($filter['TIME_to'] === '')
			{
				$recipes->addFilter('>TIME', $filter['TIME_from']);
			}
			else
			{
				$recipes->addFilter('><TIME', [$filter['TIME_from'], $filter['TIME_to']]);
			}
		}

		if (isset($filter['CALORIES_from']) or isset($filter['CALORIES_to']))
		{
			if ($filter['CALORIES_from'] === '')
			{
				$recipes->addFilter('<CALORIES', $filter['CALORIES_to']);
			}
			elseif ($filter['CALORIES_to'] === '')
			{
				$recipes->addFilter('>CALORIES', $filter['CALORIES_from']);
			}
			else
			{
				$recipes->addFilter('><CALORIES', [$filter['CALORIES_from'], $filter['CALORIES_to']]);
			}
		}

		if (isset($filter['MY_RECIPES']))
		{
			if ($filter['MY_RECIPES'] === 'Y')
			{
				$recipes->addFilter('AUTHOR_ID', $userId);
			}
			else
			{
				$recipes->addFilter('!=AUTHOR_ID', $userId);
			}
		}

		if (isset($filter['AUTHOR_ID']))
		{
			$recipes->addFilter('=AUTHOR_ID', $filter['AUTHOR_ID']);
		}

		if (isset($filter['PRODUCTS']))
		{
			$products = [];
			foreach ($filter['PRODUCTS'] as $product)
			{
				$products[] = (int)$product + 1;
			}
			//var_dump($products);
			$recipeIds = RecipeProductTable::query()->setSelect(['RECIPE_ID'])->whereIn('PRODUCT_ID', $products);
			$recipes->whereIn('ID', $recipeIds);
		}
		$recipes = $recipes->fetchAll();
		foreach ($recipes as &$recipe)
		{
			$recipe['IMAGE'] = \Up\Yummy\Repository\ImageRepository::getRecipeCover($recipe['ID']);
			if (!isset($recipe['IMAGE']))
			{
				$recipe['IMAGE'] = null;
			}
			$recipe = ValidationService::protectRecipeOutput($recipe);
		}
		return $recipes;
		//return $recipes->fetchAll();
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

	public static function getDailyRecipe(): string
	{
		$dailyRecipeId = Option::get("up.yummy", "dailyRecipeId", 1);
		$recipe = RecipesTable::getByPrimary($dailyRecipeId)->fetch()['TITLE'];
		$recipe = htmlspecialcharsEx($recipe);
		return $recipe;
	}

}