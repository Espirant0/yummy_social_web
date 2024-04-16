<?php

use Up\Yummy\Model\ImagesTable;
use Up\Yummy\Model\RecipesTable;
use Up\Yummy\Repository\ImageRepository;
use Up\Yummy\Service\ValidationService;

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$method=Bitrix\Main\Context::getCurrent()->getRequest()->isPost();
		global $USER;
		$userId = $USER->GetID();
		$id=request()['id'];

		if(ValidationService::validateRecipeId($id))
		{
			$recipe = RecipesTable::getByPrimary($id)->fetch();
			if($recipe['AUTHOR_ID']==$userId)
			{
				if($method===true)
				{
					$title = ValidationService::validateString(request()['NAME'], 50);
					$description = ValidationService::validateString(request()['DESCRIPTION'], 10000);
					$time = ValidationService::validatePositiveInteger(request()['TIME']);
					if(isset($title)&&isset($description)&&isset($time))
					{
						RecipesTable::update($id, ['TITLE' => $title, 'DESCRIPTION' => $description, 'TIME' => $time]);
						ImageRepository::updateRecipeImage($id);
						LocalRedirect("/detail/{$id}/");
					}
					else
					{
						LocalRedirect("/404/");
					}
				}
				else
				{
					$this->arResult['RECIPE'] = $recipe;
					$this->includeComponentTemplate();
				}
			}
			else
			{
				LocalRedirect("/404/");
			}
		}
		else
		{
			LocalRedirect("/404/");
		}
	}

	public function getProducts()
	{
		//$this->arResult['PRODUCTS'] = Up\Yummy\Repository\RecipeRepository::getProductsWithCategory();
	}
}