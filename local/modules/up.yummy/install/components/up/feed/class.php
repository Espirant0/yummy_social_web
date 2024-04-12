<?php

use Bitrix\Main\UI\Filter\Options;
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
			['id' => 'TITLE', 'name' => 'Название', 'type' => 'text', 'default' => true],
			['id' => 'TIME', 'name' => 'Время приготовления', 'type' => 'number', 'default' => false],
			['id' => 'CALORIES', 'name' => 'Калории', 'type' => 'number', 'default' => false],
			['id' => 'PRODUCTS', 'name' => 'Продукты', 'type' => 'list',
				'params' => ['multiple' => 'Y', 'tabs' =>
					[ 'id' => 'my-tab', 'title' => 'Моя вкладка' ],
				],
				'items' => $products,
				'default' => false],
			['id' => 'MY_RECIPES', 'name' => 'Мои рецепты', 'type' => 'checkbox', 'default' => false],
			['id' => 'FEATURED', 'name' => 'Избранное', 'type' => 'checkbox', 'default' => false],
			$user,
		];
		$filterOption = new Options('recipe_list');
		$filter = $filterOption->getFilter([$this->arParams['FILTER']]);

		$page = PaginationService::validateOffset(request()['page']);
		$this->arResult['RECIPES'] = RecipeRepository::getRecipeFeed($page, $filter);
		$pages = PaginationService::getPages($page, $this->arResult['RECIPES']);
		$this->arResult['PAGES'] = $pages;
		$this->arResult['dailyRecipe'] = RecipeRepository::getDailyRecipeTitle();
		if (count($this->arResult['RECIPES']) > PaginationService::$displayArraySize) {
			array_pop($this->arResult['RECIPES']);
		}
		//var_dump($filter);
		$this->prepareTemplateParams();
		$this->includeComponentTemplate();
	}

	protected function prepareTemplateParams(): void
	{
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}