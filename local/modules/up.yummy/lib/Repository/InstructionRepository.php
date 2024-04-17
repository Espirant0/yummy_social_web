<?php
namespace Up\Yummy\Repository;
use Up\Yummy\Model\InstructionTable;

class InstructionRepository
{
	public static function insertSteps(int $recipeId,array $steps)
	{
		foreach ($steps as $key=>$step)
		{
			InstructionTable::add(["RECIPE_ID"=>$recipeId,"STEP"=>$key+1,"DESCRIPTION"=>$step]);
		}
	}
	public static function getSteps(int $recipeId):array
	{
		$recipes=InstructionTable::query()->setSelect(['STEP','DESCRIPTION'])->setFilter(['RECIPE_ID'=>$recipeId])->fetchAll();
		return $recipes;
	}
	public static function updateSteps(int $recipeId):void
	{

	}
}