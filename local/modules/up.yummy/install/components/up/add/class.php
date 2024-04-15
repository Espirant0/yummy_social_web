<?php

use Up\Yummy\Model\ImagesTable;
use Up\Yummy\Repository\ImageRepository;
use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class AddComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		var_dump($_POST);
		global $USER;
		$userId = $USER->GetID();
		$title = ValidationService::validateString(request()['NAME'], 50);
		$description = ValidationService::validateString(request()['DESCRIPTION'], 10000);
		$time = ValidationService::validatePositiveInteger(request()['TIME']);
		switch (true)
		{
			case($title === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНОЕ НАЗВАНИЕ";
				break;
			case($description === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНОЕ ОПИСАНИЕ";
				break;
			case($time === null):
				$this->arResult['MESSAGE'] = "НЕПРАВИЛЬНОЕ ВРЕМЯ";
				break;
			default:
				$recipeID = RecipeRepository::addRecipe($title, $description, $time, $userId);
				$imageID = ImageRepository::validateImage();
				if (isset($imageID))
				{
					ImagesTable::add(['PATH' => $imageID, 'RECIPE_ID' => $recipeID, 'IS_COVER' => 1]);
				}
				LocalRedirect('/');
		}
		$this->includeComponentTemplate();
	}

}