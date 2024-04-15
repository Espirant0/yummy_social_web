<?php

namespace Up\Yummy\Integration\UI\EntitySelector;

use Bitrix\Main\Loader;
use Bitrix\UI\EntitySelector\Dialog;
use Bitrix\UI\EntitySelector\Item;
use Up\Yummy\Model\ProductsTable;

class ProductProvider extends \Bitrix\UI\EntitySelector\BaseProvider
{
	public function __construct(array $options = [])
	{
		parent::__construct();
	}
	public function isAvailable(): bool
	{
		return true;
	}

	public function getItems(array $ids) : array
	{
		$products = ProductsTable::getList([
			'select'=>
				['ID', 'NAME', 'CATEGORY_NAME' => 'CATEGORY.TITLE'],
		])->fetchAll();
		$productList = [];
		foreach ($products as $product){
			$productList[] = new Item([
				'id' => $product['ID'],
				'entityId' => 'products',
				'title' => $product['NAME'],
				'tabs' => $product['CATEGORY_NAME'],
			]);
		}
		return $productList;
	}

	public function getSelectedItems(array $ids) : array
	{
		return $this->getItems([]);
	}

	public function fillDialog(Dialog $dialog): void
	{
		$dialog->addRecentItems($this->getItems([]));
	}
}