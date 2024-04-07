<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Деталка");
$APPLICATION->IncludeComponent('up:delete', '', []);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");