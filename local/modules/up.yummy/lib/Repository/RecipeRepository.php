<?php

namespace Up\Yummy\Repository;

use Bitrix\Main\Config\Option,
	Bitrix\Main\ORM\Query\Query,
	Up\Yummy\Model\FeaturedTable,
	Up\Yummy\Model\ImagesTable,
	Up\Yummy\Model\InstructionTable,
	Up\Yummy\Model\LikesTable,
	Up\Yummy\Model\MeasuresTable,
	Up\Yummy\Model\PlannerTable,
	Up\Yummy\Model\ProductMeasuresTable,
	Up\Yummy\Model\ProductsTable,
	Up\Yummy\Model\RecipeProductTable,
	Up\Yummy\Model\RecipesTable,
	Up\Yummy\Service\ValidationService;

class RecipeRepository
{
	public static function addRecipe($title, $description, $time, $userId, $products): int|array
	{
		$recipe = RecipesTable::add([
			'TITLE' => $title,
			'DESCRIPTION' => $description,
			'TIME' => $time,
			'AUTHOR_ID' => $userId,
		]);
		foreach ($products as $product)
		{
			RecipeProductTable::add([
				'RECIPE_ID' => $recipe->getId(),
				'PRODUCT_ID' => $product[0],
				'VALUE' => $product[1],
				'MEASURE_ID' => $product[2],
			]);
		}
		return $recipe->getId();
	}

	public static function showRecipeDetail(int $id): array
	{
		$recipe = RecipesTable::query()->setSelect([
			'ID',
			'TITLE',
			'DESCRIPTION',
			'TIME',
			'AUTHOR_ID',
			'AUTHOR_NAME' => 'AUTHOR.NAME',
			'AUTHOR_SURNAME' => 'AUTHOR.LAST_NAME',
			'CALORIES',
			'PROTEINS',
			'CARBS',
			'FATS',
		])->where("ID", $id)->fetch();
		$recipe['IMAGE'] = ImageRepository::getRecipeCover($recipe['ID']);
		return ValidationService::protectRecipeOutput($recipe);
	}

	public static function getRecipeProducts(int $recipeId)
	{
		return RecipeProductTable::query()->setSelect([
			'RECIPE_ID',
			'PRODUCT_ID',
			'VALUE',
			'MEASURE_ID',
			'TITLE' => 'PRODUCT.NAME',
			'MEASURE_NAME' => 'MEASURE.TITLE',
			'COEF' => 'MEASURE.COEFFICIENT',
			'WPU' => 'PRODUCT.WEIGHT_PER_UNIT',
		])->setFilter([
			'=RECIPE_ID' => $recipeId,
		])->fetchAll();
	}

	public static function getProducts(): array
	{
		$products = ProductsTable::query()->setSelect([
			'ID',
			'NAME',
		])->fetchAll();
		$productsInJsonFormat = [];

		foreach ($products as $product)
		{
			$productsInJsonFormat[] = [
				'ID' => $product['ID'],
				'NAME' => $product['NAME'],
			];
		}
		return $productsInJsonFormat;
	}

	public static function getProductMeasures(): array
	{
		$productMeasures = ProductMeasuresTable::query()->setSelect([
			'PRODUCT_ID',
			'MEASURE_ID',
			'MEASURE_NAME' => 'MEASURE.TITLE',
		])->fetchAll();

		$productMeasuresInJsonFormat = [];
		foreach ($productMeasures as $measure)
		{
			if (!isset($productMeasuresInJsonFormat[$measure['PRODUCT_ID']]))
			{
				$productMeasuresInJsonFormat[$measure['PRODUCT_ID']] = [];
			}
			$productMeasuresInJsonFormat[$measure['PRODUCT_ID']][] = [
				'ID' => $measure['MEASURE_ID'],
				'MEASURE_NAME' => $measure['MEASURE_NAME'],
			];
		}
		return $productMeasuresInJsonFormat;
	}

	public static function deleteRecipe(int $recipeId): void
	{
		while ($recipeId == Option::get("up.yummy", "dailyRecipeId"))
		{
			$maxId = RecipesTable::query()
				->addSelect(Query::expr()->max("ID"), 'MAX_ID')
				->exec()->fetch();
			$recipeId = random_int(1, $maxId['MAX_ID']);
			$newDailyRecipeId = RecipesTable::getByPrimary($recipeId)->fetch()['ID'];
			Option::set("up.yummy", "dailyRecipeId", $newDailyRecipeId);
		}
		RecipeProductTable::deleteByFilter(['=RECIPE_ID' => $recipeId]);
		RecipesTable::getByPrimary($recipeId)->fetchObject()->delete();
		InstructionTable::deleteByFilter(['RECIPE_ID' => $recipeId]);
		ImagesTable::deleteByFilter(['RECIPE_ID' => $recipeId]);
		PlannerTable::deleteByFilter(['RECIPE_ID' => $recipeId]);
	}

	public static function addRecipeToFeatured(int $userId, int $recipeId): void
	{
		FeaturedTable::add([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $userId,
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

	public static function isRecipeInFeatured(int $userId, int $recipeId): bool
	{
		$featuredRow = FeaturedTable::getByPrimary([
			'RECIPE_ID' => $recipeId,
			'USER_ID' => $userId,
		])->fetchObject();
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
			'USER_ID' => $userId,
		])->fetchObject();
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

	public static function getRecipeTitle(int $recipeId): string
	{
		return RecipesTable::getRowById($recipeId)['TITLE'];
	}

	public static function getRecipeFeed(int $page, array $filter)
	{
		global $USER;
		$userId = $USER->GetID();
		$recipes = RecipesTable::query()
			->setSelect(['*'])
			->setOffset(7 * ($page - 1))
			->setLimit(8)
			->setOrder([
				'ID' => 'DESC',
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

		$userArray = [];
		if (isset($filter['MY_RECIPES']) && ($filter['MY_RECIPES'] === 'Y'))
		{
			$userArray[] = $userId;
		}

		if (isset($filter['AUTHOR_ID']))
		{
			foreach ($filter['AUTHOR_ID'] as $authorId)
			{
				$userArray[] = $authorId;
			}
		}
		if ($userArray !== [])
		{
			$recipes->addFilter('=AUTHOR_ID', $userArray);
		}
		if (isset($filter['PRODUCTS']))
		{
			$products = [];
			foreach ($filter['PRODUCTS'] as $product)
			{
				$products[] = (int)$product + 1;
			}
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


	public static function insertRecipeStats(int $recipeId, array $productStats): void
	{
		$stats = [
			'CALORIES' => 0,
			'PROTEINS' => 0,
			'CARBS' => 0,
			'FATS' => 0,
		];
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
		if ($dailyRecipeId != $recipeId)
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
				'MEASURE_ID' => $product[2],
			]);
		}
	}

	public static function mergeProducts(array $products): array
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
		foreach ($output as &$note)
		{
			if ($note[2] == 1 && $note[1] > 1000)
			{
				$note[2] = 2;
				$note[1] = round($note[1] / 1000, 1);
			}
			if ($note[2] == 6 && $note[1] > 1000)
			{
				$note[2] = 5;
				$note[1] = round($note[1] / 1000, 1);
			}
		}
		return $output;

	}

	private static function getMeasuresForMerge(int $productId): array
	{
		$measures = [];
		$productMeasures = ProductMeasuresTable::query()->setSelect([
			'PRODUCT_ID',
			'MEASURE_NAME' => 'MEASURE.TITLE',
			'MEASURE_COEF' => 'MEASURE.COEFFICIENT',
		])->setFilter([
			'=PRODUCT_ID' => $productId,
		])->fetchAll();
		foreach ($productMeasures as $productMeasure)
		{
			$measures[] = $productMeasure['MEASURE_NAME'];
		}
		return $measures;
	}

	private static function getWPUForMerge(int $productId): mixed
	{
		return ProductsTable::query()->setSelect([
			'WEIGHT_PER_UNIT'
		])->setFilter([
			'=ID' => $productId
		])->fetch()["WEIGHT_PER_UNIT"];
	}

	private static function getCoefForMerge(int $measureId): mixed
	{
		return MeasuresTable::query()->setSelect([
			'COEFFICIENT',
		])->setFilter([
			'=ID' => $measureId
		])->fetch()["COEFFICIENT"];
	}

	public static function checkTitleForDublicates(string $title): bool|array
	{
		return RecipesTable::query()->setSelect([
			'ID',
		])->setFilter([
			'=TITLE' => $title,
		])->fetch();
	}
}
