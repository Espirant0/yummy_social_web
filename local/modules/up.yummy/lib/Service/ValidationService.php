<?php
namespace Up\Yummy\Service;
class ValidationService
{
	public static function ValidateRecipeId($id)
	{
		if(is_string($id) && (int)$id==$id)
		{
			return true;
		}
		return false;
	}
}