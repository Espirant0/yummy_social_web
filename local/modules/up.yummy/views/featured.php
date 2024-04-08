<?php
/**
 * @var CMain $APPLICATION
 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->IncludeComponent('up:featured', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");