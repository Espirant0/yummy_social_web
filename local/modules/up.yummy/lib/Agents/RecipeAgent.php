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
		$ID=null;
		while($ID===null)
		{
			$max = RecipesTable::query()
				->addSelect(Query::expr()->max("ID"), 'MAX')
				->exec()->fetch();
			$recipeId = random_int(1, $max['MAX']);
			$ID = RecipesTable::getByPrimary($recipeId)->fetch()['ID'];
			//DailyRecipeTable::update(1,["RECIPE_ID"=>$ID]);
		}
		Option::set("up.yummy","dailyRecipeId",$ID);
		return "Up\Yummy\Agents\RecipeAgent::getDailyRecipe();";
	}

}