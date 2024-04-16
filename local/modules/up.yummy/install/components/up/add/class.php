<?php

use Up\Yummy\Model\ImagesTable;
use Up\Yummy\Repository\ImageRepository;
use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class AddComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;
		$userId = $USER->GetID();
		$title = ValidationService::validateString(request()['NAME'], 50);
		$description = ValidationService::validateString(request()['DESCRIPTION'], 10000);
		$time = ValidationService::validatePositiveInteger(request()['TIME']);
		$products = array_map(null, request()['PRODUCTS'], request()['PRODUCTS_QUANTITY'], request()['MEASURES']);
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
				$recipeId = RecipeRepository::addRecipe($title, $description, $time, $userId, $products);
				$imageId = ImageRepository::validateImage();
				if (isset($imageID))
				{
					ImagesTable::add(['PATH' => $imageId, 'RECIPE_ID' => $recipeId, 'IS_COVER' => 1]);
				}
				LocalRedirect('/');
		}
		$this->includeComponentTemplate();
	}

}