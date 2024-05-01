<?php

namespace Up\Yummy\Repository;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ORM\Query\Query;
use Up\Yummy\Model\FeaturedTable;
use Up\Yummy\Model\ImagesTable;
use Up\Yummy\Model\InstructionTable;
use Up\Yummy\Model\LikesTable;
use Up\Yummy\Model\MeasuresTable;
use Up\Yummy\Model\PlannerTable;
use Up\Yummy\Model\ProductMeasuresTable;
use Up\Yummy\Model\ProductsTable;
use Up\Yummy\Model\RecipeProductTable;
use Up\Yummy\Model\RecipesTable;
use Up\Yummy\Service\ValidationService;

class RecipeRepository
{
	public static function addRecipe($title, $description, $time, $user, $products)
	{
		$recipe = RecipesTable::add(['TITLE' => $title, 'DESCRIPTION' => $description, 'TIME' => $time, 'AUTHOR_ID' => $user]);
		foreach ($products as $product)
		{
			RecipeProductTable::add([
				'RECIPE_ID' => $recipe->getId(),
				'PRODUCT_ID' => $product[0],
				'VALUE' => $product[1],
				'MEASURE_ID' => $product[2]
			]);
		}
		return $recipe->getId();
	}

	public static function showRecipeDetail(int $id)
	{
		$recipe = RecipesTable::query()->setSelect(['*'])->where("ID", $id)->fetch();
		$recipe['IMAGE'] = ImageRepository::getRecipeCover($recipe['ID']);
		return ValidationService::protectRecipeOutput($recipe);
	}

	public static function getRecipeProducts(int $id)
	{
		$products = RecipeProductTable::getList([
			'select' =>
				['*', 'TITLE' => 'PRODUCT.NAME',
					'MEASURE_NAME' => 'MEASURE.TITLE',
					'COEF' => 'MEASURE.COEFFICIENT',
					'WPU' => 'PRODUCT.WEIGHT_PER_UNIT'
				],
			'filter' =>
				['=RECIPE_ID' => $id]
		]);
		return $products->fetchAll();
	}

	public static function getMeasures()
	{
		$measures = MeasuresTable::getList([
			'select' =>
				['ID', 'TITLE'],
		]);
		return $measures->fetchAll();
	}

	public static function getProducts(): array
	{
		$products = ProductsTable::getList([
			'select' =>
				['ID', 'NAME'],
		])->fetchAll();
		$productsInJsonFormat = [];

		foreach ($products as $product)
		{
			$productsInJsonFormat[] = ["ID" => $product["ID"], "NAME" => $product["NAME"]];
		}
		return $productsInJsonFormat;
	}

	public static function getProductMeasures(): array
	{
		$productMeasures = ProductMeasuresTable::getList([
			'select' =>
				['PRODUCT_ID', 'MEASURE_ID', 'MEASURE_NAME' => 'MEASURE.TITLE'],
		])->fetchAll();
		$productMeasuresInJsonFormat = [];

		foreach ($productMeasures as $measure)
		{
			if (!isset($productMeasuresInJsonFormat[$measure["PRODUCT_ID"]]))
			{
				$productMeasuresInJsonFormat[$measure["PRODUCT_ID"]] = [];
			}
			$productMeasuresInJsonFormat[$measure["PRODUCT_ID"]][] = [
				"ID" => $measure["MEASURE_ID"],
				"MEASURE_NAME" => $measure["MEASURE_NAME"]
			];
		}
		return $productMeasuresInJsonFormat;
	}

	public static function deleteRecipe(int $id): void
	{
		while ($id == Option::get("up.yummy", "dailyRecipeId"))
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
		InstructionTable::deleteByFilter(['RECIPE_ID' => $id]);
		ImagesTable::deleteByFilter(['RECIPE_ID' => $id]);
		PlannerTable::deleteByFilter(['RECIPE_ID' => $id]);
	}

	public static function addRecipeToFeatured(int $authorId, int $recipeId): void
	{
		FeaturedTable::add([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $authorId,
		]);
	}

	public static function likeRecipe(int $userId, int $recipeId): void
	{
		LikesTable::add([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $userId,
		]);
	}

	public static function deleteRecipeFromFeatured(int $userId, int $recipeId): void
	{
		FeaturedTable::deleteByFilter([
			'=RECIPE_ID' => $recipeId,
			'=USER_ID' => $userId,
		]);
	}

	public static function unlikeRecipe(int $userId, int $recipeId): void
	{
		LikesTable::deleteByFilter([
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

	public static function isRecipeLiked(int $userId, int $recipeId): bool
	{
		$likedRow = LikesTable::getByPrimary([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $userId,])->fetchObject();
		if ($likedRow !== null)
		{
			return true;
		}
		return false;
	}

	public static function likesCount(int $recipeId): int
	{
		return LikesTable::getCount(['=RECIPE_ID' => $recipeId]);
	}

	public static function updateRecipe()
	{
		return null;
	}

	public static function getRecipeFeed(int $page, array $filter)
	{
		global $USER;
		$userId = $USER->GetID();
		$recipes = RecipesTable::query()
			->setSelect(['*'])
			->setOffset(3 * ($page - 1))
			->setLimit(4)
			->setOrder([
				'ID' => 'DESC'
			]);

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

		if (isset($filter['LIKED']))
		{
			$recipeIds = LikesTable::query()->setSelect(['RECIPE_ID'])->setFilter(['=USER_ID' => $userId]);
			if ($filter['LIKED'] === 'Y')
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
			if (trim($filter['TITLE']) !== '')
			{
				$recipes->addFilter('%=TITLE', '%' . trim($filter['TITLE']) . '%');
			}
			if (trim($filter['FIND']) !== '')
			{
				$recipes->addFilter('%=TITLE', '%' . trim($filter['FIND']) . '%');
			}
		}

		if (isset($filter['TIME_from']) or isset($filter['TIME_to']))
		{
			if ($filter['TIME_from'] === '')
			{
				$recipes->addFilter('<TIME', round($filter['TIME_to']));
			}
			elseif ($filter['TIME_to'] === '')
			{
				$recipes->addFilter('>TIME', round($filter['TIME_from']));
			}
			else
			{
				$recipes->addFilter('><TIME', [round($filter['TIME_from']), round($filter['TIME_to'])]);
			}
		}

		if (isset($filter['CALORIES_from']) or isset($filter['CALORIES_to']))
		{
			if ($filter['CALORIES_from'] === '')
			{
				$recipes->addFilter('<CALORIES', round($filter['CALORIES_to']));
			}
			elseif ($filter['CALORIES_to'] === '')
			{
				$recipes->addFilter('>CALORIES', round($filter['CALORIES_from']));
			}
			else
			{
				$recipes->addFilter('><CALORIES', [round($filter['CALORIES_from']), round($filter['CALORIES_to'])]);
			}
		}

		if (isset($filter['FATS']) or isset($filter['FATS_to']))
		{
			if ($filter['FATS_from'] === '')
			{
				$recipes->addFilter('<FATS', round($filter['FATS_to']));
			}
			elseif ($filter['FATS_to'] === '')
			{
				$recipes->addFilter('>FATS', round($filter['FATS_from']));
			}
			else
			{
				$recipes->addFilter('><FATS', [round($filter['FATS_from']), round($filter['FATS_to'])]);
			}
		}
		if (isset($filter['CARBS']) or isset($filter['CARBS_to']))
		{
			if ($filter['CARBS_from'] === '')
			{
				$recipes->addFilter('<CARBS', round($filter['CARBS_to']));
			}
			elseif ($filter['CARBS_to'] === '')
			{
				$recipes->addFilter('>CARBS', round($filter['CARBS_from']));
			}
			else
			{
				$recipes->addFilter('><CARBS', [round($filter['CARBS_from']), round($filter['CARBS_to'])]);
			}
		}

		if (isset($filter['PROTEINS']) or isset($filter['PROTEINS_to']))
		{
			if ($filter['PROTEINS_from'] === '')
			{
				$recipes->addFilter('<PROTEINS', round($filter['PROTEINS_to']));
			}
			elseif ($filter['PROTEINS_to'] === '')
			{
				$recipes->addFilter('>PROTEINS', round($filter['PROTEINS_from']));
			}
			else
			{
				$recipes->addFilter('><PROTEINS', [round($filter['PROTEINS_from']), round($filter['PROTEINS_to'])]);
			}
		}

//		if (isset($filter['MY_RECIPES'])) {
//			if ($filter['MY_RECIPES'] === 'Y') {
//				$recipes->addFilter('AUTHOR_ID', $userId);
//			} else {
//				$recipes->addFilter('!=AUTHOR_ID', $userId);
//			}
//		}
//
//		if (isset($filter['AUTHOR_ID'])) {
//			$recipes->addFilter('=AUTHOR_ID', $filter['AUTHOR_ID']);
//		}
		$userArray=[];
		if(isset($filter['MY_RECIPES'])&&($filter['MY_RECIPES'] === 'Y'))
		{
			$userArray[]=$userId;
		}

		if (isset($filter['AUTHOR_ID']))
		{
			foreach ($filter['AUTHOR_ID'] as $authorId)
			{
				$userArray[]=$authorId;
			}
		}
		if($userArray!==[])
		{
			$recipes->addFilter('=AUTHOR_ID', $userArray);
		}
		if (isset($filter['PRODUCTS'])) {
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
			$recipe['IMAGE'] = ImageRepository::getRecipeCover($recipe['ID']);
			if (!isset($recipe['IMAGE']))
			{
				$recipe['IMAGE'] = null;
			}
			$recipe = ValidationService::protectRecipeOutput($recipe);
			$recipe['LIKES_COUNT'] = self::likesCount($recipe['ID']);
		}
		return $recipes;
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

	public static function insertRecipeStats(int $recipeId, array $productStats): void
	{
		$stats = ['CALORIES' => 0, 'PROTEINS' => 0, 'CARBS' => 0, 'FATS' => 0];
		foreach ($productStats as $productStat)
		{

			$product = ProductsTable::getByPrimary($productStat[0])->fetch();
			$measure = MeasuresTable::getByPrimary($productStat[2])->fetch();
			if ($measure['ID'] == 7)
			{
				$calories = $product['CALORIES'] * $productStat[1] * $product['WEIGHT_PER_UNIT'] / 100;
				$proteins = $product['PROTEINS'] * $productStat[1] * $product['WEIGHT_PER_UNIT'] / 100;
				$carbs = $product['CARBS'] * $productStat[1] * $product['WEIGHT_PER_UNIT'] / 100;
				$fats = $product['FATS'] * $productStat[1] * $product['WEIGHT_PER_UNIT'] / 100;
			}
			else
			{
				$calories = $product['CALORIES'] * $productStat[1] * $measure['COEFFICIENT'] / 100;
				$proteins = $product['PROTEINS'] * $productStat[1] * $measure['COEFFICIENT'] / 100;
				$carbs = $product['CARBS'] * $productStat[1] * $measure['COEFFICIENT'] / 100;
				$fats = $product['FATS'] * $productStat[1] * $measure['COEFFICIENT'] / 100;
			}
			$stats['CALORIES'] += $calories;
			$stats['PROTEINS'] += $proteins;
			$stats['CARBS'] += $carbs;
			$stats['FATS'] += $fats;

		}
		RecipesTable::update($recipeId,
			[
				'CALORIES' => round($stats['CALORIES']),
				'PROTEINS' => round($stats['PROTEINS']),
				'CARBS' => round($stats['CARBS']),
				'FATS' => round($stats['FATS']),
			]);
	}

	public static function getDailyRecipe(): array
	{
		$dailyRecipeId = Option::get("up.yummy", "dailyRecipeId");
		$recipe = RecipesTable::getByPrimary($dailyRecipeId)->fetch();
		$recipe['IMAGE'] = ImageRepository::getRecipeCover($dailyRecipeId);
		return $recipe;
	}

	public static function isRecipeDaily(int $recipeId): bool
	{
		$dailyRecipeId = Option::get("up.yummy", "dailyRecipeId");
		if ($dailyRecipeId !== $recipeId)
		{
			return false;
		}
		return true;
	}

	public static function updateDailyRecipe(): void
	{
		$recipeId = null;
		while ($recipeId === null)
		{
			$max = RecipesTable::query()
				->addSelect(Query::expr()->max("ID"), 'MAX')
				->exec()->fetch();
			$recipeId = random_int(1, $max['MAX']);
			$recipeId = RecipesTable::getByPrimary($recipeId)->fetch()['ID'];
		}
		Option::set("up.yummy", "dailyRecipeId", $recipeId);
	}

	public static function updateProducts(int $recipeId, array $products): void
	{
		RecipeProductTable::deleteByFilter(['=RECIPE_ID' => $recipeId]);
		foreach ($products as $product)
		{
			RecipeProductTable::add([
				'RECIPE_ID' => $recipeId,
				'PRODUCT_ID' => $product[0],
				'VALUE' => $product[1],
				'MEASURE_ID' => $product[2]
			]);
		}
	}

	public static function mergeProducts(array $products)
	{
		$productArray = [];
		$output = [];
		foreach ($products as $product)
		{
			if ($productArray[$product['0']] === null)
			{
				$productArray[$product['0']] = 1;
			}
			else
			{
				$productArray[$product['0']]++;
			}
		}
		foreach ($products as $product)
		{
			$measures = self::getMeasuresForMerge($product['0']);
			$product['WPU'] = self::getWPUForMerge($product['0']);
			$product['COEF'] = self::getCoefForMerge($product['2']);
			$productArray[$product['0']]--;
			if ($productArray[$product['0']] === 0 && $output[$product['0']] === null)
			{
				$output[$product['0']] = $product;
			}
			else if ($output[$product['0']] === null)
			{
				if ($product['2'] != 7)
				{
					$product['1'] = $product['1'] * $product['COEF'];
				}
				else
				{
					$product['1'] = $product['1'] * $product['WPU'];
				}
				if (in_array('гр', $measures) || in_array('кг', $measures))
				{
					$product['2'] = 1;
				}
				else
				{
					$product['2'] = 6;
				}
				$output[$product['0']] = $product;
			}
			else
			{
				if ($product['2'] != 7)
				{
					$output[$product['0']]['1'] += $product['1'] * $product['COEF'];
				}
				else
				{
					$output[$product['0']]['1'] += $product['1'] * $product['WPU'];
				}
			}
		}
		foreach($output as &$note)
		{
			if($note[2]==1&&$note[1]>1000)
			{
				$note[2]=2;
				$note[1]=round($note[1]/1000,1);
			}
			if($note[2]==6&&$note[1]>1000)
			{
				$note[2]=5;
				$note[1]=round($note[1]/1000,1);
			}
		}
		return $output;

	}

	private static function getMeasuresForMerge($productId)
	{
		$measures = [];
		$productMeasures = ProductMeasuresTable::getList([
			'select' =>
				['PRODUCT_ID',
					'MEASURE_NAME' => 'MEASURE.TITLE',
					'MEASURE_COEF' => 'MEASURE.COEFFICIENT',
				],
			'filter' => ['=PRODUCT_ID' => $productId]
		])->fetchAll();
		foreach ($productMeasures as $productMeasure)
		{
			$measures[] = $productMeasure['MEASURE_NAME'];
		}
		return $measures;
	}

	private static function getWPUForMerge($productId)
	{
		return ProductsTable::getList(
			['select' => ['WEIGHT_PER_UNIT'],
				'filter' => ['=ID' => $productId]]
		)->fetch()["WEIGHT_PER_UNIT"];
	}

	private static function getCoefForMerge($measureId)
	{
		return MeasuresTable::getList(
			['select' => ['COEFFICIENT'],
				'filter' => ['=ID' => $measureId]]
		)->fetch()["COEFFICIENT"];
	}

	public static function checkForDublicates($name)
	{
		return RecipesTable::getList([
			'select' => [
				'ID'
			],
			'filter' => [
				'=TITLE' => $name
			]
		])->fetch();
	}
}
