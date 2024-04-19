<?php

namespace Up\Yummy\Agents;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ORM\Query\Query;
use Up\Yummy\Model\RecipesTable;
use Up\Yummy\Model\DailyRecipeTable;

class RecipeAgent
{
	public static function getDailyRecipe()
	{
		$recipeId = null;
		while ($recipeId === null) {
			$max = RecipesTable::query()
				->addSelect(Query::expr()->max("ID"), 'MAX')
				->exec()->fetch();
			$recipeId = random_int(1, $max['MAX']);
			$recipeId = RecipesTable::getByPrimary($recipeId)->fetch()['ID'];
		}
		Option::set("up.yummy", "dailyRecipeId", $recipeId);
		return "Up\Yummy\Agents\RecipeAgent::getDailyRecipe();";
	}

}