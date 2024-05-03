<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/editForm.bundle.css',
	'js' => 'dist/editForm.bundle.js',
	'rel' => [
		'main.polyfill.core',
	],
	'skip_core' => true,
];
