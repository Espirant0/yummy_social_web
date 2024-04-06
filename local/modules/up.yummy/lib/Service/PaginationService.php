<?php

namespace Up\Yummy\Service;
class PaginationService
{
	public static int $displayArraySize = 3;
	public static int $selectArraySize = 4;

	public static function getPages(int $page, array $array )
	{
		$pages = [];
		if ($page > 1)
		{
			$pages[] = $page - 1;
		}
		else
		{
			$pages[] = $page ;
		}
		if (count($array) < self::$selectArraySize)
		{
			$pages[] = $page;
		}
		else
		{
			$pages[] = $page + 1;
		}
		return $pages;
	}
	public static function validateOffset($offset)
	{
		if (is_string($offset) && (int)$offset == $offset && $offset > 0)
		{
			return (int)$offset;
		}
		return 1;
	}


}