<?php
/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Tasker documentation");

$APPLICATION->IncludeComponent('up:task.doc', '', []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
