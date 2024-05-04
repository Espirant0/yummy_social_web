<?php

namespace Up\Yummy\Repository;

use Up\Yummy\Model\InstructionTable,
	Up\Yummy\Service\ValidationService;

class InstructionRepository
{
	public static function insertSteps(int $recipeId, array $steps): void
	{
		foreach ($steps as $stepNumber => $step)
		{
			InstructionTable::add(["RECIPE_ID" => $recipeId, "STEP" => $stepNumber + 1, "DESCRIPTION" => $step]);
		}
	}

	public static function getSteps(int $recipeId): array
	{
		$recipes = InstructionTable::query()->setSelect(['STEP', 'DESCRIPTION'])->setFilter(['RECIPE_ID' => $recipeId])->fetchAll();
		return ValidationService::protectStepsOutput($recipes);
	}

	public static function updateSteps(int $recipeId, array $steps): void
	{
		InstructionTable::deleteByFilter(['=RECIPE_ID' => $recipeId]);
		self::insertSteps($recipeId, $steps);
	}
}