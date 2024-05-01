<?php

namespace Up\Yummy\Controller;

use Up\Yummy\Model\FeaturedTable;
use Up\Yummy\Model\LikesTable;
use Up\Yummy\Repository\RecipeRepository;

class Detail extends \Bitrix\Main\Engine\Controller
{
	public function likeAction($recipe, $user):void
	{
		if(LikesTable::getRow([
				'filter' => [
					'=RECIPE_ID' => $recipe,
					'=USER_ID' => $user,
				]
			]) === null)
		{
			RecipeRepository::likeRecipe((int)$user, (int)$recipe);
		}
		else
		{
			RecipeRepository::unlikeRecipe((int)$user, (int)$recipe);
		}

	}

	public function addToFeaturedAction($recipe, $user):void
	{
		if(FeaturedTable::getRow([
				'filter' => [
					'=RECIPE_ID' => $recipe,
					'=USER_ID' => $user,
				]
			]) === null)
		{
			RecipeRepository::addRecipeToFeatured((int)$user, (int)$recipe);
		}
		else
		{
			RecipeRepository::deleteRecipeFromFeatured((int)$user, (int)$recipe);
		}

	}

	public function getLikesCountAction($recipe):int
	{
		return RecipeRepository::likesCount((int)$recipe);
	}

	public function isRecipeLikedAction($user, $recipe):bool
	{
		return RecipeRepository::isRecipeLiked((int)$user, (int)$recipe);
	}

	public function isRecipeInFeaturedAction($user, $recipe):bool
	{
		return RecipeRepository::isRecipeInFeatured((int)$user, (int)$recipe);
	}
}