<?php

return [
	'plugin' => [
		'name' => 'Profile River Updates',
		'version' => '4.0.0',
	],
	'events' => [
		'profileupdate' => [
			'user' => [
				"\ProfileRiverUpdatesEvents::profile_river_updates" => [],
			],
		],
	],
];