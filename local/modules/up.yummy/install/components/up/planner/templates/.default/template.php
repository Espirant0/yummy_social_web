<?php
/**
 * @var DateTime $currentTime ;
 * @var $arResult
 */

\Bitrix\Main\UI\Extension::load('up.planner');
\Bitrix\Main\UI\Extension::load("ui.hint");

$weekStart = $arResult['START_DATE'];
$user = $arResult['USER'];
$jsFormatDate = date("D M d Y H:i:s \G\M\TO (T)", $weekStart);
?>
<form action="/" method="get" class="create_btn">
	<button class="ui-btn ui-btn-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<div class="buttons" id="buttons">
		<a href="/planner/?day=<?= date('d.m.Y', $arResult['PREV_WEEK']) ?>" id="prev_week_btn"
		   class="ui-btn ui-btn-primary">Предыдущая неделя</a>
		<a href="/planner/" class="ui-btn ui-btn-primary" id="current_week_btn">Текущая неделя</a>
		<a href="/planner/?day=<?= date('d.m.Y', $arResult['NEXT_WEEK']) ?>" id="next_week_btn"
		   class="ui-btn ui-btn-primary">Следующая неделя</a>
	</div>
	<div class="current_date">
		<p class="has-text-centered">Сегодня <?= $arResult['CURR_DATE'] ?></p>
		<span data-hint="При нажатии на дату в таблице вы можете увидеть список продуктов на этот день"></span>
	</div>
	<table class="table is-bordered planner_table">
		<tbody class="planner_table" id="planner_table">
		</tbody>
	</table>
	<div class="title has-text-centered">Список продуктов на неделю</div>
	<table id="daily_product_table">
	</table>
	<div class="product_table">
		<table id="product_table">
		</table>
	</div>
	<div id="modal">
	</div>
</div>

<script>
	BX.ready(function () {
		BX.UI.Hint.init(BX('info'));
		window.YummyPlanner = new BX.Up.Yummy.Planner({
			rootNodeId: 'planner_table',
			currentDate: new Date('<?=$jsFormatDate?>'),
			start: <?=$weekStart?>,
			userId: '<?=$user?>',
		});
	});
</script>