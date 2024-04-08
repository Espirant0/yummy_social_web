<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
	$routes->get('/', new PublicPageController('/local/modules/up.yummy/views/feed.php'));
	$routes->get('/planner/', new PublicPageController('/local/modules/up.yummy/views/planner.php'));
	$routes->get('/create/', new PublicPageController('/local/modules/up.yummy/views/create.php'));
	$routes->get('/detail/{id}/', new PublicPageController('/local/modules/up.yummy/views/detail.php'));
	$routes->post('/detail/{id}/', new PublicPageController('/local/modules/up.yummy/views/detail.php'));
	$routes->post('/delete/', new PublicPageController('/local/modules/up.yummy/views/delete.php'));
	$routes->post('/featured/', new PublicPageController('/local/modules/up.yummy/views/featured.php'));
	$routes->post('/publish/', new PublicPageController('/local/modules/up.yummy/views/publish.php'));
	$routes->get('/404/', new PublicPageController('/local/modules/up.yummy/views/404.php'));

};