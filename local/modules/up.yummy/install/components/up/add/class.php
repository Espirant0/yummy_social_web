<?php

use Up\Yummy\Repository\ValidationRepository;

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;
		$userId=$USER->GetID();
		$title=ValidationRepository::validateString(request()['NAME'],50);
		$description=ValidationRepository::validateString(request()['DESCRIPTION'],10000);
		$time= ValidationRepository::validatePositiveInteger(request()['TIME']);
		switch (true)
		{
			case($title===null):
				$this->arResult['MESSAGE']="НЕПРАВИЛЬНОЕ НАЗВАНИЕ";
				break;
			case($description===null):
				$this->arResult['MESSAGE']="НЕПРАВИЛЬНОЕ ОПИСАНИЕ";
				break;
			case($time===null):
				$this->arResult['MESSAGE']="НЕПРАВИЛЬНОЕ ВРЕМЯ";
				break;
			default:
				$RecipeID=Up\Yummy\Repository\RecipeRepository::addRecipe($title,$description,$time,$userId);
				$ImageID=\Up\Yummy\Repository\ImageRepository::validateImage();
				\Up\Yummy\Model\ImagesTable::add(['PATH'=>$ImageID,'RECIPE_ID'=>$RecipeID,'IS_COVER'=>1]);
				LocalRedirect('/');
		}
		$this->includeComponentTemplate();
	}

}