<?php
/**
 * @var $arResult
 */
?>
<div class="content">
	<div class="buttons">
		<a href="/planner/?date=<?=date('d.m.Y', $arResult['PREV_WEEK'])?>" class="button">Предыдущая неделя</a>
		<a href="/planner/" class="button">Текущая неделя</a>
		<a href="/planner/?date=<?=date('d.m.Y',$arResult['NEXT_WEEK'])?>" class="button">Следующая неделя</a>
	</div>
	<p class="has-text-centered">Сегодня <?=$arResult['CURR_DATE']?></p>
	<table class="table is-bordered is-hoverable planner_table">
		<thead>
		<tr>
			<th class="is-info"></th>
			<?php for ($i = 0;$i < 7;$i++):?>
				<th class="is-info is-primary"><?=$arResult['WEEK_DAYS'][$i]?> <br> <?=date("d.m.Y", strtotime('+'.$i.' day', $arResult['START_DATE']))?></th>
			<?php endfor;?>
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
	</table>
</div>

<a href="#x" class="overlay" id="win1"></a>
<div class="popup">
	Окно
	<a class="close" title="Закрыть" href="#close"></a>
</div>