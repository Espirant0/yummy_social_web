<?php
namespace Up\Yummy\Service;
class ValidationService
{
	public static function validateRecipeId($id)
	{
		if(is_string($id) && (int)$id==$id)
		{
			return true;
		}
		return false;
	}
	public static function protectRecipeOutput($recipe)
	{
		$recipe['TITLE']=htmlspecialcharsEx($recipe['TITLE']);
		$recipe['DESCRIPTION']=htmlspecialcharsEx($recipe['DESCRIPTION']);
		$recipe['TIME']=htmlspecialcharsEx($recipe['TIME']);
		return $recipe;
	}
	public static function validatePositiveInteger($integer):mixed
	{
		if((int)$integer==$integer && $integer>=0)
		{
			return (int)$integer;
		}
		return null;
	}
	public static function validateString($string,int $stringLength):mixed
	{
		if(!is_string($string)|| $string === '')
		{
			return null;
		}
		$string=substr($string,0,$stringLength);
		return $string;
	}
	public static function protectStepsOutput($steps)
	{
		foreach ($steps as &$step)
		{
			$step['DESCRIPTION']=htmlspecialcharsEx($step['DESCRIPTION']);
		}
		return $steps;
	}
}