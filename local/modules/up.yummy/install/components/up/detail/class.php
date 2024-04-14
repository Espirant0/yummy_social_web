<?php

use Up\Yummy\Repository\RecipeRepository;
use Up\Yummy\Service\ValidationService;

class DetailComponent extends CBitrixComponent
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
		global $USER;
		$userId = $USER->GetID();
		$recipeId = request()['id'];
		$this->arResult['FEATURED'] = RecipeRepository::isRecipeInFeatured($userId, $recipeId);
		if(ValidationService::validateRecipeId($recipeId))
		{
			$this->arResult['AUTHOR_ID'] = $userId;
			$this->arResult['RECIPE'] = RecipeRepository::showRecipeDetail($recipeId);
			$this->arResult['PRODUCTS'] = RecipeRepository::getRecipeProducts($recipeId);
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