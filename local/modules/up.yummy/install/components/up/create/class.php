<?php

use Up\Yummy\Repository\RecipeRepository;

class CreateComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareRecipeParams();
		$this->includeComponentTemplate();
	}

	protected function prepareRecipeParams(): void
	{
		$this->arResult['PRODUCTS'] = RecipeRepository::getProducts();
		$this->arResult['PRODUCT_MEASURES'] = RecipeRepository::getProductMeasures();
	}
}