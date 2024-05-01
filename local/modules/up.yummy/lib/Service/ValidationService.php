<?php
namespace Up\Yummy\Service;
use Up\Yummy\Model\ProductMeasuresTable;

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
	public static function validateProductAmount($amount):mixed
	{
		if(!is_array($amount)||in_array("",$amount,true)||count($amount)>10)
		{
			return null;
		}
		foreach ($amount as $value)
		{
			if($value < 1)
			{
				return null;
			}
		}
		return $amount;

	}
	public static function validateSteps($steps):mixed
	{
		if(!is_array($steps)||empty($steps)||count($steps)>10)
		{
			return null;
		}
		if (in_array("", $steps, true))
		{
			return null;
		}
		return $steps;
	}
	public static function checkForIllegalIDs(array $products):bool
	{
		foreach ($products as $product)
		{
			$pair=ProductMeasuresTable::query()->setSelect(['*'])
				->setFilter(["=PRODUCT_ID"=>$product[0]])
				->setFilter(["=MEASURE_ID"=>$product[2]])->fetch();
			if($pair===false)
			{
				return false;
			}
		}
		return true;
	}
}