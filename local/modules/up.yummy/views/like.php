<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->IncludeComponent('up:like', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");