<?php

use Up\Yummy\Repository\RecipeRepository;

class LikeComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$recipeId = request()['recipeId'];
		global $USER;
		$userId = (int)$USER->GetID();
		if(!RecipeRepository::isRecipeLiked($userId, $recipeId))
		{
			RecipeRepository::likeRecipe($userId, $recipeId);
		}
		else
		{
			RecipeRepository::unlikeRecipe($userId, $recipeId);
		}
		LocalRedirect("/detail/$recipeId/");
	}
}

