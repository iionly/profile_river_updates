<?php

class ProfileRiverUpdatesEvents {

	public static function profile_river_updates(\Elgg\Event $event) {
		$user = $event->getObject();

		if ($user instanceof ElggUser) {
			$view = "river/user/default/profileupdate";

			elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user, $view) {
				$river_items = elgg_get_river([
					'action_type' => 'update',
					'subject_guid' => $user->guid,
					'batch' => true,
					'batch_inc_offset' => false,
					'limit' => false,
					'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) use ($view) {
						return $qb->compare('rv.view', '=', $view, ELGG_VALUE_STRING);
					},
				]);

				foreach ($river_items as $river_item) {
					$river_item->delete();
				}
			});

			// Then create new river entry informing about profile update of this user
			elgg_create_river_item([
				'view' => $view,
				'action_type' => 'update',
				'subject_guid' => $user->guid,
				'object_guid' => $user->guid,
			]);
		}

		return true;
	}
	
}