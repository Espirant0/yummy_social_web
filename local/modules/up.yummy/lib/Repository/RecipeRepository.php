<?php
namespace Up\Yummy\Repository;
use Up\Yummy\Model\RecipeProductTable;
use Up\Yummy\Model\RecipesTable;

class RecipeRepository
{
	public static function addRecipe()
	{
		return null;
	}
	public static function showRecipeDetail(int $id)
	{
		$recipe=RecipesTable::query()->setSelect(['*'])->where("ID",$id)->fetchObject();
		return $recipe;
	}
	public static function getRecipeProducts(int $id)
	{
		$products=RecipeProductTable::getList([
		'select'=>
			['*','TITLE'=>'PRODUCT.NAME', 'MEASURE_NAME' => 'MEASURE.TITLE'],
		'filter'=>
			['=RECIPE_ID'=>$id]
		]);
		return $products->fetchAll();

	}
	public static function deleteRecipe(int $id):void
	{
		RecipesTable::getByPrimary($id)->fetchObject()->delete();;
	}
	public static function validateRecipeAuthor(int $authorId,int $recipeId):bool
	{
		$recipe=RecipesTable::getByPrimary($recipeId)->fetchObject();
		if($recipe['AUTHOR_ID']===$authorId)
		{
			return true;
		}
		return false;

	}
	public static function updateRecipe()
	{
		return null;
	}
	public static function getRecipeFeed(int $page)
	{
		$recipes=RecipesTable::query()->setSelect(['*'])->setOffset(3*($page-1))->setLimit(4);
		$recipes=$recipes->fetchAll();
		return $recipes;
	}
}