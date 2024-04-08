<?php

use Up\Yummy\Repository\RecipeRepository;

class DeleteComponent extends CBitrixComponent
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