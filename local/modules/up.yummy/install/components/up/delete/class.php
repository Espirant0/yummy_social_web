<?php

use Up\Yummy\Repository\RecipeRepository;

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$recipeId=request()['deleteId'];
		global $USER;
		$userId=(int)$USER->GetID();
		var_dump($userId);
		if(RecipeRepository::validateRecipeAuthor($userId,$recipeId))
		{
			RecipeRepository::deleteRecipe($recipeId);
			LocalRedirect("/");
		}
		$this->includeComponentTemplate();
	}
}