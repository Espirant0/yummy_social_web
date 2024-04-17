<div class="content">
	<div class="buttons">
		<button class="button">Предыдущая неделя</button>
		<button class="button">Текущая неделя</button>
		<button class="button">Следующая неделя</button>
	</div>
	<!--<table class="table is-bordered is-hoverable planner_table">
		<thead>
		<tr>
			<th class="is-info"></th>
			<th class="is-info">Понедельник <br> 08.04.2024</th>
			<th class="is-info">Вторник <br> 09.04.2024</th>
			<th class="is-info">Среда <br> 10.04.2024</th>
			<th class="is-info">Четверг <br> 11.04.2024</th>
			<th class="is-info">Пятница <br> 12.04.2024</th>
			<th class="is-info">Суббота <br> 13.04.2024</th>
			<th class="is-info">Воскресенье <br> 14.04.2024</th>
		</tr>
		</thead>
		<tbody class="planner_table">
		<tr>
			<th class="is-info">Завтрак</th>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
		</tr>
		<tr>
			<th class="is-info">Обед</th>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
		</tr>
		<tr>
			<th class="is-info">Ужин</th>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
			<td>
				<a href="#win1">+</a>
			</td>
		</tr>
		</tbody>
	</table>-->
	<?php
	// Определение текущей недели и текущего дня
	$currentWeek = date('W');
	$currentDay = date('N');

	// Проверка, входит ли текущий день в текущую неделю
	if ($currentDay < 1 || $currentDay > 7) {
		// Обновление недели, если текущий день не входит в текущую неделю
		$currentWeek = date('W');
	}

	// Функция для отображения календаря
	function displayCalendar($week) {
		// Получение даты начала и конца недели
		$startDate = date('Y-m-d', strtotime($week . 'W1'));
		$endDate = date('Y-m-d', strtotime($week . 'W7'));

		// Ваш код для отображения календаря на указанную неделю
		// Например, можно использовать цикл для вывода дней недели и их дат

		echo "Календарь для недели $week:";
		echo "<br>";
		echo "Начало недели: $startDate";
		echo "<br>";
		echo "Конец недели: $endDate";
	}

	// Отображение календаря для текущей недели
	/*displayCalendar($currentWeek);

	// Переход к предыдущей неделе
	$previousWeek = $currentWeek - 1;
	displayCalendar($previousWeek);

	// Переход к следующей неделе
	$nextWeek = $currentWeek + 1;
	displayCalendar($nextWeek);*/
	$currentDate = date('Y-m-d');

	// Определение дня недели для текущей даты (от 1 до 7, где 1 - понедельник, 7 - воскресенье)
	$currentDayOfWeek = date('N', strtotime($currentDate));

	// Вычисление даты начала недели
	$startDate = date('Y-m-d', strtotime('-' . ($currentDayOfWeek - 1) . ' days', strtotime($currentDate)));

	// Вычисление даты конца недели
	$endDate = date('Y-m-d', strtotime('+' . (7 - $currentDayOfWeek) . ' days', strtotime($currentDate)));

	// Вывод даты начала и конца недели
	echo "Дата начала недели: $startDate";
	echo "<br>";
	echo "Дата конца недели: $endDate";
	?>
</div>

<a href="#x" class="overlay" id="win1"></a>
<div class="popup">
	Окно
	<a class="close" title="Закрыть" href="#close"></a>
</div>