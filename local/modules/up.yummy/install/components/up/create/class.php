<?php

use Up\Yummy\Repository\RecipeRepository;

class CreateComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->arResult['PRODUCTS'] = RecipeRepository::getProducts();
		$this->arResult['MEASURES'] = RecipeRepository::getMeasures();
		$this->includeComponentTemplate();
	}
}