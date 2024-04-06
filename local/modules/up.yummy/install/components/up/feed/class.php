<?php

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->arResult['recipes']=Up\Yummy\Repository\RecipeRepository::getRecipeFeed();
		$this->prepareTemplateParams();
		$this->includeComponentTemplate();
	}

	protected function prepareTemplateParams(): void
	{
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}