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
		global $USER;
		$userId = $USER->GetID();
		$title = ValidationService::validateString(request()['NAME'], 50);
		$description = ValidationService::validateString(request()['DESCRIPTION'], 250);
		$time = ValidationService::validatePositiveInteger(request()['TIME']);
		$steps=ValidationService::validateSteps(request()['STEPS']);

		$amount=ValidationService::validateProductAmount(request()['PRODUCTS_QUANTITY']);
		if($this->handleExceptions($title,$description,$time,$steps,$amount))
		{
			$products = array_map(null, request()['PRODUCTS'], $amount, request()['MEASURES']);
			$recipeId=$this->createRecipe($title,$description,$time,$userId,$products,$steps);
			LocalRedirect('/');
		}
		$this->includeComponentTemplate();
	}
	protected function handleExceptions($title,$description,$time,$steps,$amount):bool
	{
		ValidationService::validateProductAmount(request()['PRODUCTS_QUANTITY']);
		switch (true)
		{
			case($title === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНОЕ НАЗВАНИЕ";
				return false;
			case($description === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНОЕ ОПИСАНИЕ";
				return false;
			case($time === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНОЕ ВРЕМЯ";
				return false;
			case($amount === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНО ПЕРЕДАНЫ ПРОДУКТЫ";
				return false;
			case($steps === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНО ПЕРЕДАНЫ ШАГИ";
				return false;
			case(RecipeRepository::checkForDublicates($title)!==false):
				$this->arResult['MESSAGE'] = "РЕЦЕПТ С ТАКИМ НАЗВАНИЕМ УЖЕ ЕСТЬ";
				return false;

			default:
				return true;
		}
	}
	protected function addImage(int $recipeId)
	{
		$imageId = ImageRepository::validateImage();
		if (isset($imageId))
		{
			ImagesTable::add(['PATH' => $imageId, 'RECIPE_ID' => $recipeId, 'IS_COVER' => 1]);
		}
	}
	protected function createRecipe(string $title,string $description,int $time,int $userId,array $products,array $steps)
	{
		$products=RecipeRepository::mergeProducts($products);
		$recipeId = RecipeRepository::addRecipe($title, $description, $time, $userId, $products);
		RecipeRepository::insertRecipeStats($recipeId, $products);
		InstructionRepository::insertSteps($recipeId, $steps);
		$this->addImage($recipeId);
		return $recipeId;
	}

}