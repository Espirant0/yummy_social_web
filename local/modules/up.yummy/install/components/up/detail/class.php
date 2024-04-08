<?php

use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class DetailComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;
		$userId = $USER->GetID();
		$recipeId = request()['id'];
		$this->arResult['FEATURED'] = RecipeRepository::isRecipeInFeatured($userId, $recipeId);
		$this->arResult['IS_PUBLIC'] = RecipeRepository::isRecipeInPublic($recipeId);
		if(ValidationService::validateRecipeId($recipeId))
		{
			$this->arResult['AUTHOR_ID'] = $userId;
			$this->arResult['RECIPE'] = RecipeRepository::showRecipeDetail($recipeId);
			$this->arResult['PRODUCTS'] = RecipeRepository::getRecipeProducts($recipeId);
			$this->prepareTemplateParams();
			$this->includeComponentTemplate();
		}
		else
		{
			LocalRedirect('/404/');
		}

	}

	protected function prepareTemplateParams(): void
	{
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}