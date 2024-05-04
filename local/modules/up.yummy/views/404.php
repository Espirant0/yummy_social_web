<?php

/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Ошибка 404");
$APPLICATION->IncludeComponent('up:404', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");