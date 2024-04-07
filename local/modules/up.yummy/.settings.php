<?php
return [
	'ui.entity-selector' => [
		'value' => [
			'entities' => [
				[
					'entityId' => 'my-module-entity',
					'provider' => [
						'moduleId' => 'my-module',
						'className' => '\\Vendor\\MyModule\\Integration\\UI\\EntitySelector\\MyProvider'
					],
				],
			],
			'extensions' => ['my-module.entity-selector'],
		],
		'readonly' => true,
	]
];