<?php
// Файл /js/my-module/entity-selector/config.php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
if (!\Bitrix\Main\Loader::includeModule('up.yummy'))
{
	return [];
}
return [
	'settings' => [
		'entities' => [
			[
				'id' => 'products',
				'options' => [
					'dynamicLoad' => true, // определяет динамическую загрузку данных с бэкенда
					'dynamicSearch' => true, // определяет динамический поиск

					// Задает дополнительные поля для поиска
					'searchFields' => [
						[ 'name' => 'position', 'type' => 'string' ],
						[ 'name' =>  'email', 'type' => 'email' ]
					],
					// Внешний вид элементов
					'itemOptions' => [
						'default' => [
							'supertitle' => 'Мой надзаголовок',
							'avatar' => '/path/to/image.svg', // аватар элементов по умолчанию
							'link' =>  '/service/entity/#id#/',
							'linkTitle' => 'узнать подробнее',
						],
						'inactive' => [ // для эл-тов, у которых entityType='inactive' будет выводиться бейдж 'Неактивный'
							'badges' => [
								[
									'title' => 'Неактивный',
									'textColor' => '#828b95',
									'bgColor' => '#eaebec',
								]
							],
						],
					],
					// Внешний вид элементов в виджете TagSelector
					'tagOptions' => [
						'default' => [
							'textColor' => '#1066bb',
							'bgColor' => '#bcedfc',
							'avatar' => '/path/to/image.svg', // аватар элементов в виджете TagSelector по умолчанию
						],
						'inactive' => [
							'textColor' => '#5f6670',
							'bgColor' => '#ecedef',
						],
					],
					// Дополнительные настройки бейджей
					'badgeOptions' => [
						[
							'title' => 'В отпуске',
							'bgColor' => '#b4f4e6',
							'textColor' => '#27a68a',
							'conditions' => [
								'isOnVacation' =>  true
							],
						],
					],
				],
			],
		]
	]
];