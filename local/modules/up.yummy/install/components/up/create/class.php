<?php

class TaskDocComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		//$this->getProducts();
		$this->includeComponentTemplate();
	}

	public function getProducts()
	{
		//$this->arResult['PRODUCTS'] = Up\Yummy\Repository\RecipeRepository::getProductsWithCategory();
	}
}