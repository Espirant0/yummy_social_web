<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("НЕТ ТАКОЙ СТРАНИЦЫ");
$APPLICATION->IncludeComponent('up:404', '', []);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");