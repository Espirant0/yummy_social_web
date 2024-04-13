<?php
namespace Up\Yummy\Repository;
class ValidationRepository
{
	public static function validateString($string,int $stringLength):mixed
	{
		if(!is_string($string))
		{
			return null;
		}
		$string=substr($string,0,$stringLength);
		return $string;
	}
	public static function validatePositiveInteger($integer):bool
	{
		if((int)$integer==$integer && $integer>=0)
		{
			return true;
		}
		return false;
	}
}