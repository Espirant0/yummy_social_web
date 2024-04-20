<?php

class PlannerComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->getDates();
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

		if (isset(request()['date']) && is_numeric(strtotime(request()['date'])))
		{
			$date = request()['date'];
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
}