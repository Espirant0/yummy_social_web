<?php

use Up\Yummy\Repository\RecipeRepository;

class FeaturedComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$recipeId = request()['recipeId'];
		global $USER;
		$userId = (int)$USER->GetID();
		if(!RecipeRepository::isRecipeInFeatured($userId, $recipeId))
		{
			RecipeRepository::addRecipeToFeatured($userId, $recipeId);
		}
		else
		{
			RecipeRepository::deleteRecipeFromFeatured($userId, $recipeId);
		}
		LocalRedirect("/detail/$recipeId/");
	}
}

