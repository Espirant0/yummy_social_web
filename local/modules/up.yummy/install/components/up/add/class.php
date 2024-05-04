<?php

use Up\Yummy\Model\ImagesTable;
use Up\Yummy\Repository\ImageRepository;
use Up\Yummy\Repository\InstructionRepository;
use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class AddComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->validateRecipeData();
		$this->includeComponentTemplate();
	}

	protected function handleExceptions($title, $description, $time, $steps, $amount): bool
	{
		ValidationService::validateProductAmount(request()['PRODUCTS_QUANTITY']);
		switch (true)
		{
			case($title === null):
				$this->arResult['MESSAGE'] = "неправильное название";
				return false;
			case($description === null):
				$this->arResult['MESSAGE'] = "Неправильное описание";
				return false;
			case($time === null):
				$this->arResult['MESSAGE'] = "Неправильное время";
				return false;
			case($amount === null):
				$this->arResult['MESSAGE'] = "Неправильно переданы продукты";
				return false;
			case($steps === null):
				$this->arResult['MESSAGE'] = "Неправильно переданы шаги";
				return false;
			case(RecipeRepository::checkTitleForDublicates($title) !== false):
				$this->arResult['MESSAGE'] = "Рецепт с таким названием уже есть";
				return false;
			default:
				return true;
		}
	}

	protected function validateRecipeData(): void
	{
		if (!check_bitrix_sessid())
		{
			die('Ошибка отправки формы! Проверьте данные и повторите попытку.');
		}
		global $USER;
		$userId = $USER->GetID();
		$title = ValidationService::validateString(request()['NAME'], 50);
		$description = ValidationService::validateString(request()['DESCRIPTION'], 250);
		$time = ValidationService::validatePositiveInteger(request()['TIME']);
		$steps = ValidationService::validateSteps(request()['STEPS']);
		$productsAmount = ValidationService::validateProductAmount(request()['PRODUCTS_QUANTITY']);
		if ($this->handleExceptions($title, $description, $time, $steps, $productsAmount))
		{
			$products = array_map(null, request()['PRODUCTS'], $productsAmount, request()['MEASURES']);
			if (ValidationService::checkForIllegalIDs($products) === true)
			{
				$recipeId = $this->createRecipe($title, $description, $time, $userId, $products, $steps);
				LocalRedirect("/detail/$recipeId/");
			}
			else
			{
				$this->arResult['MESSAGE'] = "Неправильные данные о продукте";
			}
		}
	}

	protected function addImage(int $recipeId): void
	{
		$imageId = ImageRepository::validateImage();
		if (isset($imageId))
		{
			ImagesTable::add(['PATH' => $imageId, 'RECIPE_ID' => $recipeId, 'IS_COVER' => 1]);
		}
	}

	protected function createRecipe(string $title, string $description, int $time, int $userId, array $products, array $steps): array|int
	{
		$products = RecipeRepository::mergeProducts($products);
		$recipeId = RecipeRepository::addRecipe($title, $description, $time, $userId, $products);
		RecipeRepository::insertRecipeStats($recipeId, $products);
		InstructionRepository::insertSteps($recipeId, $steps);
		$this->addImage($recipeId);
		return $recipeId;
	}

}