<?php

/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Создайте лучший рецепт в мире!!!!!!!!!!!!!!!1");

$APPLICATION->IncludeComponent('up:create', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
