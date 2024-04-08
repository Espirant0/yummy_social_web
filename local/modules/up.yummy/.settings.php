<?php
return [
	'ui.entity-selector' => [
		'value' => [
			'entities' => [
				[
					'entityId' => 'products',
					'provider' => [
						'moduleId' => 'up.yummy',
						'className' => '\\Up\\Yummy\\Integration\\UI\\EntitySelector\\ProductProvider'
					],
				],
			],
			'extensions' => ['up.yummy.entity-selector'],
		],
		'readonly' => true,
	]
];