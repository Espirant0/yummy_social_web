<?php

/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Добавить рецепт");

$APPLICATION->IncludeComponent('up:add', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");