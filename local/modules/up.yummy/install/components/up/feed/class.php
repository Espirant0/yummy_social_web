<?php

use Bitrix\Main\Config\Option;

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$page=\Up\Yummy\Service\PaginationService::validateOffset(request()['page']);
		$this->arResult['RECIPES']=Up\Yummy\Repository\RecipeRepository::getRecipeFeed($page);
		$pages=\Up\Yummy\Service\PaginationService::getPages($page,$this->arResult['RECIPES']);
		$this->arResult['PAGES']=$pages;
		$this->arResult['dailyRecipe']=\Up\Yummy\Repository\RecipeRepository::getDailyRecipeTitle();
		if (count($this->arResult['RECIPES']) > \Up\Yummy\Service\PaginationService::$displayArraySize)
		{
			array_pop($this->arResult['RECIPES']);
		}
		$this->prepareTemplateParams();
		$this->includeComponentTemplate();
	}

	protected function prepareTemplateParams(): void
	{
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}