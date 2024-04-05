<?php

class DetailComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$id=request()['id'];
		if(\Up\Yummy\Service\ValidationService::ValidateRecipeId($id))
		{
			$this->arResult['RECIPE'] = Up\Yummy\Repository\RecipeRepository::showRecipeDetail($id);
			$this->arResult['PRODUCTS'] = Up\Yummy\Repository\RecipeRepository::getRecipeProducts($id);
			$this->prepareTemplateParams();
			$this->includeComponentTemplate();
		}
		else
		{
			LocalRedirect('/404/');

		}

	}

	protected function prepareTemplateParams(): void
	{
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}