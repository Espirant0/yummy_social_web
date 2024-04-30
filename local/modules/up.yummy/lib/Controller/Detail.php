<?php

namespace Up\Yummy\Controller;

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
}