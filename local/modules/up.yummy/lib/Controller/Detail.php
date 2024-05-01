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
			RecipeRepository::likeRecipe($user, $recipe);
		}
		else
		{
			RecipeRepository::unlikeRecipe($user, $recipe);
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
			RecipeRepository::addRecipeToFeatured($user, $recipe);
		}
		else
		{
			RecipeRepository::deleteRecipeFromFeatured($user, $recipe);
		}

	}

	public function getLikesCountAction($recipe):int
	{
		return RecipeRepository::likesCount($recipe);
	}

	public function isRecipeLikedAction($user, $recipe):bool
	{
		return RecipeRepository::isRecipeLiked($user, $recipe);
	}

	public function isRecipeInFeaturedAction($user, $recipe):bool
	{
		return RecipeRepository::isRecipeInFeatured($user, $recipe);
	}
}