<?php

/**
 * @var $arResult
 *
 */


$recipe = $arResult['RECIPE'];

?>
<form action="/update/<?= $recipe['ID'] ?>/" method="get" class="create_btn">
	<button class="button is-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
		<?=$arResult['MESSAGE']?>
</div>

