<?php

namespace Up\Yummy\Service;

use Up\Yummy\Model\ProductMeasuresTable;
use Up\Yummy\Model\RecipesTable;

class ValidationService
{
	public static function validateRecipeId($id): bool
	{
		if (is_string($id) && (int)$id == $id)
		{
			return true;
		}
		return false;
	}

	public static function protectRecipeOutput($recipe): array
	{
		$recipe['TITLE'] = htmlspecialcharsEx($recipe['TITLE']);
		$recipe['DESCRIPTION'] = htmlspecialcharsEx($recipe['DESCRIPTION']);
		$recipe['TIME'] = htmlspecialcharsEx($recipe['TIME']);
		return $recipe;
	}

	public static function validatePositiveInteger($integer): ?int
	{
		if ((int)$integer == $integer && $integer >= 0)
		{
			return (int)$integer;
		}
		return null;
	}

	public static function validateString($string, int $stringLength): ?string
	{
		if (!is_string($string) || $string === '')
		{
			return null;
		}
		return substr($string, 0, $stringLength);
	}

	public static function protectStepsOutput($steps): array
	{
		foreach ($steps as &$step)
		{
			$step['DESCRIPTION'] = htmlspecialcharsEx($step['DESCRIPTION']);
		}
		return $steps;
	}

	public static function validateProductAmount($amount): ?array
	{
		if (!is_array($amount) || in_array("", $amount, true) || count($amount) > 15)
		{
			return null;
		}
		foreach ($amount as $value)
		{
			if ($value < 1)
			{
				return null;
			}
		}
		return $amount;

	}

	public static function validateSteps($steps): ?array
	{
		if (!is_array($steps) || empty($steps) || count($steps) > 10)
		{
			return null;
		}
		if (in_array("", $steps, true))
		{
			return null;
		}
		return $steps;
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

	public static function checkForIllegalIDs(array $products): bool
	{
		foreach ($products as $product)
		{
			$pair = ProductMeasuresTable::query()->setSelect(['*'])
				->setFilter(["=PRODUCT_ID" => $product[0]])
				->setFilter(["=MEASURE_ID" => $product[2]])->fetch();
			if ($pair === false)
			{
				return false;
			}
		}
		return true;
	}

	public static function protectPlannerRecipeOutput($recipes, $title = 'TITLE')
	{
		foreach ($recipes as &$recipe)
		{
			$recipe[$title] = htmlspecialcharsEx($recipe[$title]);
		}
		return $recipes;
	}
}