<?php

use Up\Yummy\Repository\ValidationRepository;
use Up\Yummy\Service\ValidationService;

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;
		$userId=$USER->GetID();
		$title=ValidationService::validateString(request()['NAME'],50);
		$description=ValidationService::validateString(request()['DESCRIPTION'],10000);
		$time= ValidationService::validatePositiveInteger(request()['TIME']);
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