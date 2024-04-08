<?php

namespace Up\Yummy\Integration\UI\EntitySelector;

use Bitrix\Main\Loader;
use Bitrix\UI\EntitySelector\BaseProvider;
use Bitrix\UI\EntitySelector\Item;
use Up\Yummy\Model\ProductsTable;

class ProductProvider extends BaseProvider
{
	public function __construct(array $options = [])
	{
		parent::__construct();
	}
	public function isAvailable(): bool
	{
		if (!Loader::includeModule('up.yummy'))
		{
			return false;
		}
		return true;
		//return parent::isAvailable();
	}

	public function getItems(array $ids) : array
	{
		$products = ProductsTable::getList([
			'select'=>
				['ID', 'NAME', 'CATEGORY_NAME' => 'CATEGORY.TITLE'],
		])->fetchAll();
		$productList = [];
		foreach ($products as $product){
			$customData = [
				'category' => $product['CATEGORY_NAME'],
			];
			$productList[] = new Item([
				'id' => $product['ID'],
				'entityId' => 'products',
				'title' => $product['NAME'],
				'customData' => $customData,
			]);
		}
		return $productList;
	}

	public function getSelectedItems(array $ids) : array
	{
		$products = ProductsTable::getList([
			'select'=>
				['ID', 'NAME', 'CATEGORY_NAME' => 'CATEGORY.TITLE'],
		]);
		$productList = [];
		foreach ($products as $product){
			$customData = [
				'category' => $product['CATEGORY_NAME'],
			];
			$productList[] = new Item([
				'id' => $product['ID'],
				'entityId' => 'products',
				'title' => $product['NAME'],
				'customData' => $customData,
			]);
		}
		return $productList;
	}
}