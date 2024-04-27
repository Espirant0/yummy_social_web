<?php
namespace Up\Yummy\Repository;
use Up\Yummy\Model\MeasuresTable;
use Up\Yummy\Model\ProductsTable;

class MeasureRepository
{
	public static function getMeasureName($id)
	{
		return MeasuresTable::getByPrimary($id)->fetchObject()['TITLE'];
	}
}