<?php

use Up\Yummy\Repository\RecipeRepository;

class PublishComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$recipeId = request()['recipeId'];
		if(!RecipeRepository::isRecipeInPublic($recipeId))
		{
			RecipeRepository::addRecipeToFeed($recipeId);
		}
		else
		{
			RecipeRepository::removeRecipeFromFeed($recipeId);
		}
		LocalRedirect("/detail/$recipeId/");
	}
}

