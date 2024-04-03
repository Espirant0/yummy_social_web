<?php
/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Tasker documentation");

$APPLICATION->IncludeComponent('up:feed', '', []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
