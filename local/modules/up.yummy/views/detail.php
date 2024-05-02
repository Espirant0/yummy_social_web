<?php

/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$title = \Up\Yummy\Repository\RecipeRepository::getRecipeTitle(request()['id']);
$APPLICATION->SetTitle($title);

$APPLICATION->IncludeComponent('up:detail', '', []);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");