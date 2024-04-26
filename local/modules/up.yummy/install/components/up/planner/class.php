<?php

class PlannerComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->getUserId();
		$this->getDates();
		$recipes=\Up\Yummy\Repository\PlannerRepository::getPlanForWeek($this->arResult['START_DATE']);
		$this->arResult['PRODUCTS']=\Up\Yummy\Repository\PlannerRepository::getProductsForWeek($recipes);
		$this->includeComponentTemplate();
	}

	protected function getDates(): void
	{
		$this->arResult['WEEK_DAYS'] = [
			"Понедельник",
			"Вторник",
			"Среда",
			"Четверг",
			"Пятница",
			"Суббота",
			"Воскресенье",
		];

		$this->arResult['CURR_DATE'] = date('d.m.Y');

		if (isset(request()['day']) && is_numeric(strtotime(request()['day'])))
		{
			$date = request()['day'];
			$currentDayOfWeek = date('N', strtotime($date));
		}
		else
		{
			$date = $this->arResult['CURR_DATE'];
			$currentDayOfWeek = date('N', strtotime($date));
		}

		$this->arResult['START_DATE'] = strtotime(date('d.m.Y', strtotime('-' . ($currentDayOfWeek - 1) . ' days', strtotime($date))));

		$secondsInWeek = 604800;
		$this->arResult['NEXT_WEEK'] = strtotime($date) + $secondsInWeek;
		$this->arResult['PREV_WEEK'] = strtotime($date) - $secondsInWeek;
	}

	protected function getUserId()
	{
		global $USER;
		$this->arResult['USER'] = (int)$USER->GetID();
	}
}