<?php

namespace Up\Yummy\Controller;

use Bitrix\Bizproc\Error;
use Up\Yummy\Repository\PlannerRepository;

class Planner extends \Bitrix\Main\Engine\Controller
{
	public function getListAction($user, $start): ?array
	{
		return PlannerRepository::getPlanForWeek($user, $start);
	}
	public function getCoursesAction(): ?array
	{
		return PlannerRepository::getCourses();
	}

	public function getProductsAction($user, $start): ?array
	{
		$recipes = PlannerRepository::getPlanForWeek($user, $start);
		return PlannerRepository::getProducts($recipes);
	}

	public function getDailyProductsAction($date, $user): ?array
	{
		$recipes = PlannerRepository::getDailyPlan($user, $date);
		return PlannerRepository::getProducts($recipes);
	}

	public function editPlanAction($date, $course, $recipe, $user):void
	{
		if(PlannerRepository::isPlanExists($date, $course, $user))
		{
			PlannerRepository::deletePlan($date, $course, $user);
		}
		PlannerRepository::addPlan($date, $course, $recipe, $user);
	}

	public function deletePlanAction($date, $course, $user):void
	{
		if(PlannerRepository::isPlanExists($date, $course, $user))
		{
			PlannerRepository::deletePlan($date, $course, $user);
		}
	}

	public function getRecipeListAction():?array
	{
		return PlannerRepository::getRecipeList();
	}
}