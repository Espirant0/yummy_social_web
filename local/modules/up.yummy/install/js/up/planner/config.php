<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/project-list.bundle.css',
	'js' => 'dist/project-list.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];