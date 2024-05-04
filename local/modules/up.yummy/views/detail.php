<?php

/**
 * @var CMain $APPLICATION
 */

use Up\Yummy\Repository\RecipeRepository;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$title = RecipeRepository::getRecipeTitle(request()['id']);
$APPLICATION->SetTitle($title);
$APPLICATION->IncludeComponent('up:detail', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");