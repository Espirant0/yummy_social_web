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
			['product_id','value'],
		'filter'=>
			['=PRODUCT_ID',$id]
		]);
		return $products->fetchAll();

	}
	public static function deleteRecipe()
	{
		return null;
	}
	public static function updateRecipe()
	{
		return null;
	}
}