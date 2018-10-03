<?php

elgg_register_event_handler('init', 'system', 'profile_river_updates_init');

function profile_river_updates_init() {
	elgg_register_event_handler('profileupdate', 'user', 'profile_river_updates');
}

function profile_river_updates($event, $type, $user) {
	if ($user instanceof ElggUser) {
		$view = "river/user/default/profileupdate";

		// First delete any existing river entry about profile updates of this user
		$access = elgg_set_ignore_access(true);
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$river_items = elgg_get_river([
			'action_type' => 'update',
			'subject_guid' => $user->guid,
			'batch' => true,
			'batch_inc_offset' => false,
			'limit' => false,
			'wheres' => ["rv.view = \"$view\""],
		]);

		foreach ($river_items as $river_item) {
			$river_item->delete();
		}

		access_show_hidden_entities($access_status);
		elgg_set_ignore_access($access);

		// Then create new river entry informing about profile update of this user
		elgg_create_river_item([
			'view' => $view,
			'action_type' => 'update',
			'subject_guid' => $user->guid,
			'object_guid' => $user->guid,
			'access_id' => get_default_access($user),
		]);
	}

	return true;
}
