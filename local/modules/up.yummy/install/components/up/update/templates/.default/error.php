<?php
/**
 * @var $arResult
 * @var $arParams
 */

?>
<h1>Ошибка создания</h1>
<?php foreach ($arResult['MESSAGE'] as $message):?>
<h1>
    <?=$message?>
</h1>
<?php endforeach;?>
<form action="/update/<?=$arParams['ID']?>/" method="get" class="create_btn">
    <button class="ui-btn ui-btn-success" id="comeback_btn">Назад</button>
</form>