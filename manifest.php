<?php

return [
	'name' => 'ai-catsuggest',
	'config' => [
		'config',
	],
	'depends' => [
		'aimeos-core',
		'ai-client-html',
	],
	'include' => [
		'src',
	],
	'setup' => [
		'setup',
	],
	'template' => [
		'client/html/templates' => [
			'templates/client/html',
		],
	],
];
