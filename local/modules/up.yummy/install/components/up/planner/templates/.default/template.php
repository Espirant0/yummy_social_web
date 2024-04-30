<?php
/**
 * @var DateTime $currentTime;
 * @var $arResult
 */

\Bitrix\Main\UI\Extension::load('up.planner');

$timestamp =  $arResult['START_DATE'];
$user = $arResult['USER'];
$newDate = date("D M d Y H:i:s \G\M\TO (T)", $timestamp);
?>
<form action="/" method="get" class="create_btn">
	<button class="button is-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<div class="buttons" id="buttons">
		<a href="/planner/?day=<?=date('d.m.Y', $arResult['PREV_WEEK'])?>" id="prev_week_btn" class="button">Предыдущая неделя</a>
		<a href="/planner/" class="button" id="current_week_btn">Текущая неделя</a>
		<a href="/planner/?day=<?=date('d.m.Y',$arResult['NEXT_WEEK'])?>" id="next_week_btn" class="button">Следующая неделя</a>
	</div>
	<p class="has-text-centered">Сегодня <?=$arResult['CURR_DATE']?></p>
	<table class="table is-bordered is-hoverable planner_table">
		<tbody class="planner_table" id="planner_table">
		</tbody>
	</table>
	<table id="daily_product_table">
	</table>
	<table id="product_table">
	</table>
	<div id="modal">
	</div>
</div>

<script>
	BX.ready(function (){
		window.YummyPlanner = new BX.Up.Yummy.Planner({
			rootNodeId: 'planner_table',
			currentDate: new Date('<?=$newDate?>'),
			start: <?=$timestamp?>,
			userId: '<?=$user?>',
		});
	});
</script>