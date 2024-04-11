<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;
use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\PaginationService;

class FeedComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$user = [
			'id' => 'AUTHOR_ID',
			'name' => 'Пользователь',
			'type' => 'entity_selector',
			'params' => [
				'multiple' => 'Y',
				'dialogOptions' => [
					'height' => 240,
					'context' => 'filter',
					'entities' => [
						[
							'id' => 'user',
							'options' => [
								'inviteEmployeeLink' => false
							],
						],
					]
				],
			],
		];
		$products = RecipeRepository::getProducts();
		$this->arParams['FILTER_ID'] = 'recipe_list';
		$this->arParams['FILTER'] = [
			['id' => 'TITLE', 'name' => 'Название', 'type'=>'text', 'default' => true],
			['id' => 'TIME', 'name' => 'Время приготовления', 'type'=>'number', 'default' => false],
			['id' => 'CALORIES', 'name' => 'Калории', 'type'=>'number', 'default' => false],
			['id' => 'PRODUCTS', 'name' => 'Продукты', 'type'=>'list',
				'params' => ['multiple' => 'Y'],
				'items' => $products,
				'default' => false],
			['id' => 'MY_RECIPES', 'name' => 'Мои рецепты', 'type'=>'checkbox', 'default' => false],
			['id' => 'FEATURED', 'name' => 'Избранное', 'type'=>'checkbox', 'default' => false],
			$user,
		];
		$filterOption = new Bitrix\Main\UI\Filter\Options('recipe_list');
		$filterData = $filterOption->getFilter([$this->arParams['FILTER']]);
		$filterRecipes = [];
		$filterFeatured = 'N';

		foreach ($filterData as $filterItem => $filterValue) {
			if(isset($filterData['TITLE']))
			{
				$filterRecipes['TITLE'] = "%" . $filterData['TITLE'] . "%";
			}
			else{
				$filterRecipes['TITLE'] = "%" . $filterData['FIND'] . "%";
			}
			if(isset($filterData['TIME_from']))
			{
				$filterRecipes['TIME'] = (float)$filterData['TIME_from'];
			}
			if(isset($filterData['CALORIES_from']))
			{
				$filterRecipes['CALORIES'] = (float)$filterData['CALORIES_from'];
			}
			if(isset($filterData['AUTHOR_ID']))
			{
				$filterRecipes['AUTHOR_ID'] = (int)$filterData['AUTHOR_ID'];
			}
			if(isset($filterData['FEATURED']))
			{
				$filterFeatured = $filterData['FEATURED'];
			}
		}

		$page= PaginationService::validateOffset(request()['page']);
		$this->arResult['RECIPES'] = RecipeRepository::getRecipeFeed($page, $filterRecipes);
		$pages= PaginationService::getPages($page,$this->arResult['RECIPES']);
		$this->arResult['PAGES']=$pages;
		$this->arResult['dailyRecipe']= RecipeRepository::getDailyRecipeTitle();
		if (count($this->arResult['RECIPES']) > PaginationService::$displayArraySize)
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