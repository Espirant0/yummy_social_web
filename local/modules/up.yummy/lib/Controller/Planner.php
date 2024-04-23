<?php

namespace Up\Yummy\Controller;

class Planner extends \Bitrix\Main\Engine\Controller
{
	public function getListAction(): ?array
	{
		return [
			[
				'id' => 1,
				'name' => 'test 1',
			],
			[
				'id' => 2,
				'name' => 'test 2',
			],
		];
	}
}