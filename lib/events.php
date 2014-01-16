<?php
/**
 * All event handler functions for this plugin can be found in this file.
 */

/**
 * When an expert leaves the group, remove the expert role
 *
 * @param string $event the 'leave' event
 * @param string $type for the 'group' type
 * @param array $params the provided params
 *
 * @return void
 */
function questions_leave_group_handler($event, $type, $params) {
	
	if (!empty($params) && is_array($params)) {
		$user = elgg_extract("user", $params);
		$group = elgg_extract("group", $params);
		
		if (!empty($user) && elgg_instanceof($user, "user") && !empty($group) && elgg_instanceof($group, "group")) {
			// is the user an expert in this group
			if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $group->getGUID())) {
				// remove the expert role
				remove_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $group->getGUID());
			}
		}
	}
}
