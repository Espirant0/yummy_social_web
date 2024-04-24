<?php

use Up\Yummy\Model\ImagesTable;
use Up\Yummy\Model\InstructionTable;
use Up\Yummy\Model\RecipesTable;
use Up\Yummy\Repository\ImageRepository;
use Up\Yummy\Repository\InstructionRepository;
use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class UpdateComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$method = Bitrix\Main\Context::getCurrent()->getRequest()->isPost();
		$id = request()['id'];

		if (ValidationService::validateRecipeId($id))
		{
			$this->updateRecipe($id, $method);
		}
		else
		{
			LocalRedirect("/404/");
		}
	}

	protected function updateRecipe(int $recipeId, bool $method): void
	{
		global $USER;
		$userId = $USER->GetID();
		$recipe = RecipesTable::getByPrimary($recipeId)->fetch();
		if ($recipe['AUTHOR_ID'] == $userId) {
			if ($method === true)
			{
				$title = ValidationService::validateString(request()['NAME'], 50);
				$description = ValidationService::validateString(request()['DESCRIPTION'], 250);
				$time = ValidationService::validatePositiveInteger(request()['TIME']);
				$steps =ValidationService::validateSteps(request()['STEPS']);
				$products =request()['PRODUCTS'];
				$productsQuantity = ValidationService::validateProductAmount(request()['PRODUCTS_QUANTITY']);
				$measures = request()['MEASURES'];
				$photoStatus=(int)request()['photoStatus'];
				if (isset($title, $description, $time, $productsQuantity, $products,$steps)) {
					$this->insertRecipe($recipeId, $title, $description, $time, $products, $steps, $productsQuantity, $measures,$photoStatus );
					LocalRedirect("/detail/{$recipeId}/");
				}
				else
				{
					LocalRedirect("/404/");
				}
			}
			else
			{
				$this->prepareRecipeData($recipeId, $recipe);
				$this->includeComponentTemplate();
			}
		}
		else
		{
			LocalRedirect("/404/");
		}
	}

	protected function prepareRecipeData(int $recipeId, array $recipe):void
	{
		$this->arResult['USED_PRODUCTS'] = RecipeRepository::getRecipeProducts($recipeId);
		$this->arResult['PRODUCTS'] = RecipeRepository::getProducts();
		$this->arResult['PRODUCT_MEASURES'] = RecipeRepository::getProductMeasures();
		$this->arResult['RECIPE'] = $recipe;
		$this->arResult['STEPS'] = ValidationService::protectStepsOutput(InstructionRepository::getSteps($recipeId));
		$this->arResult['PRODUCTS_SIZE'] = count($this->arResult['USED_PRODUCTS']);
		$this->arResult['STEPS_SIZE'] = count($this->arResult['STEPS']);
		$this->arResult['IMAGE']=ImageRepository::getRecipeCover($recipeId);
	}

	protected function insertRecipe
	(
		int $recipeId,
		string $title,
		string $description,
		int $time,
		array $products,
		array $steps,
		array $productsQuantity,
		array $measures,
		int $photoStatus
	):void
	{

		RecipesTable::update($recipeId, ['TITLE' => $title, 'DESCRIPTION' => $description, 'TIME' => $time]);
		$productsList = array_map(null, $products, $productsQuantity, $measures);
		$productsList=RecipeRepository::mergeProducts($productsList);
		RecipeRepository::updateProducts($recipeId, $productsList);
		ImageRepository::updateRecipeImage($recipeId,$photoStatus);
		InstructionRepository::updateSteps($recipeId, $steps);
		RecipeRepository::insertRecipeStats($recipeId,$productsList);
	}
}