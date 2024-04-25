<?php

namespace Up\Yummy\Controller;

use Bitrix\Bizproc\Error;
use Up\Yummy\Repository\PlannerRepository;

class Planner extends \Bitrix\Main\Engine\Controller
{
	public function getListAction(): ?array
	{
		return PlannerRepository::getPlan();
	}
	public function getCoursesAction(): ?array
	{
		return PlannerRepository::getCourses();
	}
}