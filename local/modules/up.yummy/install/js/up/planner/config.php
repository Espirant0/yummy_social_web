<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/planner.bundle.css',
	'js' => 'dist/planner.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];