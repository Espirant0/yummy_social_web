<?php

use Up\Yummy\Repository\InstructionRepository;
use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class DetailComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;
		$userId = $USER->GetID();
		$recipeId = request()['id'];
		if(ValidationService::validateRecipeId($recipeId))
		{
			$this->arResult['RECIPE'] = RecipeRepository::showRecipeDetail($recipeId);
			if($this->arResult['RECIPE']['TITLE']==="")
			{
				LocalRedirect("/404/");
			}
			$this->arResult['LIKES_COUNT'] = RecipeRepository::likesCount($recipeId);
			$this->arResult['LIKED'] = RecipeRepository::isRecipeLiked($userId, $recipeId);
			$this->arResult['FEATURED'] = RecipeRepository::isRecipeInFeatured($userId, $recipeId);
			$this->arResult['AUTHOR_ID'] = $userId;
			$this->arResult['PRODUCTS'] = RecipeRepository::getRecipeProducts($recipeId);
			$this->arResult['STEPS']=InstructionRepository::getSteps($recipeId);
			$this->arResult['PRODUCTS'] =RecipeRepository::mergeProducts(RecipeRepository::getRecipeProducts($recipeId));
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