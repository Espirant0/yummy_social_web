<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\UI\Filter\Options;
use Up\Yummy\Repository\PlannerRepository;
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
								'inviteEmployeeLink' => false,
								'all-users' => true,
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
			['id' => 'FATS', 'name' => 'Жиры', 'type' => 'number', 'default' => false],
			['id' => 'CARBS', 'name' => 'Углеводы', 'type' => 'number', 'default' => false],
			['id' => 'PROTEINS', 'name' => 'Белки', 'type' => 'number', 'default' => false],
			['id' => 'PRODUCTS', 'name' => 'Продукты', 'type' => 'list',
				'params' => ['multiple' => 'Y', 'tabs' =>
					['id' => 'my-tab', 'title' => 'Моя вкладка'],
				],
				'items' => $products,
				'default' => false
			],
			['id' => 'MY_RECIPES', 'name' => 'Мои рецепты', 'type' => 'checkbox', 'default' => false],
			['id' => 'FEATURED', 'name' => 'Избранное', 'type' => 'checkbox', 'default' => false],
			['id' => 'LIKED', 'name' => 'Понравилось мне', 'type' => 'checkbox', 'default' => false],
			$user,
		];
		global $USER;
		$userId = $USER->GetID();
		$date = new DateTime('today');
		$date = $date->format('Y-m-d');
		$filterOption = new Options('recipe_list');
		$filter = $filterOption->getFilter([$this->arParams['FILTER']]);
		$page = PaginationService::validateOffset(request()['page']);
		$this->arResult['RECIPES'] = RecipeRepository::getRecipeFeed($page, $filter);
		$this->arResult['NOT_FOUND_MESSAGE'] = 'По вашему запросу ничего найдено.';
		$this->arResult['PAGES'] = PaginationService::getPages($page, $this->arResult['RECIPES']);
		$this->arResult['DAILY_RECIPE'] = RecipeRepository::getDailyRecipe();
		$this->arResult['PAGE'] = $page;
		$this->arResult['PLANNER_COURSES'] = PlannerRepository::getCourses();
		$this->arResult['PLANNER_RECIPES'] = PlannerRepository::getDailyPlan($userId, $date);
		if (count($this->arResult['RECIPES']) > PaginationService::$displayArraySize)
		{
			array_pop($this->arResult['RECIPES']);
		}

		$this->prepareTemplateParams();
		$this->includeComponentTemplate();
	}

	protected function prepareTemplateParams(): void
	{
		$this->arParams['CLOCK_ICON'] = $this->getPath() . '/images/clock-regular.svg';
		$imagePath = $this->getPath() . '/images/default_image.jpg';
		$this->arParams['IMAGE'] = $imagePath;
	}
}