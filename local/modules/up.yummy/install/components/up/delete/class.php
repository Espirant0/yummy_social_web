<?php

use Up\Yummy\Repository\RecipeRepository;

class DeleteComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$recipeId=(int)request()['deleteId'];
		global $USER;
		$userId=(int)$USER->GetID();
		if(RecipeRepository::validateRecipeAuthor($userId,$recipeId))
		{
			RecipeRepository::deleteRecipe($recipeId);
			if(RecipeRepository::isRecipeDaily($recipeId))
			{
				RecipeRepository::updateDailyRecipe();
			}
			LocalRedirect("/");
		}
		$this->includeComponentTemplate();
	}
}