<?php

elgg_register_event_handler('init','system','profile_river_updates_init');

function profile_river_updates_init() {
	elgg_register_event_handler('profileupdate','user','profile_river_updates');
}

function profile_river_updates($event, $type, $object) {

	$user = get_entity($object->guid);

	if ($user instanceof ElggUser) {
		$view = 'river/user/default/profileupdate';
		elgg_delete_river(array('subject_guid' => $user->guid, 'view' => $view));
		add_to_river($view, 'update', $user->guid, $user->guid, get_default_access($user));
	}

	return true;
}
