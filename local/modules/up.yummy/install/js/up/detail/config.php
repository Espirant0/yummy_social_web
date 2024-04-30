<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/detail.bundle.css',
	'js' => 'dist/detail.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];
