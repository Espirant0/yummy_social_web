<?php

/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("");

$APPLICATION->IncludeComponent('up:detail.doc', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");