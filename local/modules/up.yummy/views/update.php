<?php

/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Изменить рецепт");
$APPLICATION->IncludeComponent('up:update', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
