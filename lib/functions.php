<?php
/**
 * All helper functions for the questions plugin can be found in this file.
 */

/**
 * This function checks if expert roles are enabled in the plugin settings
 *
 * @return bool true is enabled, false otherwise
 */
function questions_experts_enabled() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		$setting = elgg_get_plugin_setting("experts_enabled", "questions");
		if ($setting == "yes") {
			$result = true;
		}
	}
	
	return $result;
}

/**
 * Check if a user is an expert
 *
 * @param ElggEntity $container the container where a question was asked
 * @param ElggUser $user the user to check (defaults to current user)
 *
 * @return bool true if the user is an expert, false otherwise
 */
function questions_is_expert(ElggEntity $container, ElggUser $user = null) {
	$result = false;
	
	// make sure we have a user
	if (empty($user) || !elgg_instanceof($user, "user")) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!empty($container) && !empty($user) && elgg_instanceof($user, "user")) {
		// the container has to be a ElggSite or ElggGroup, but can be an ElggUser
		if (elgg_instanceof($container, "user")) {
			$container = elgg_get_site_entity();
		}
		
		if (elgg_instanceof($container, "site") || elgg_instanceof($container, "group")) {
			// admins are always experts
			if ($user->isAdmin()) {
				$result = true;
			} elseif (elgg_instanceof($container, "group") && $container->canEdit()) {
				// group owners are experts in their own groups
				$result = true;
			} elseif (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $container->getGUID())) {
				// user has the expert role
				$result = true;
			}
		}
	}
	
	return $result;
}

/**
 * Check if the user can mark this answer as the correct one
 *
 * @param ElggAnswer $entity the answer to check
 * @param ElggUser $user the use who is wants to do the action (defaults to current user)
 *
 * @return bool true if the user is allowed to mark, false otherwise
 */
function questions_can_mark_answer(ElggAnswer $entity, ElggUser $user = null) {
	$result = false;
	static $experts_only;
	
	// check if we have a user
	if (empty($user) || !elgg_instanceof($user, "user")) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!empty($user) && !empty($entity) && elgg_instanceof($entity, "object", "answer")) {
		$container = $entity->getContainerEntity();
		
		// are experts enabled
		if (!questions_experts_enabled()) {
			// no, so only question owner can mark
			if ($user->getGUID() == $container->getOwnerGUID()) {
				$result = true;
			}
		} else {
			// get plugin setting for who can mark the answer
			if (!isset($experts_only)) {
				$experts_only = false;
				
				$setting = elgg_get_plugin_setting("experts_mark", "questions");
				if ($setting == "yes") {
					$experts_only = true;
				}
			}
			
			// are only experts allowed to mark
			if (!$experts_only) {
				// no, so the owner of a question can also mark
				if ($user->getGUID() == $container->getOwnerGUID()) {
					$result = true;
				}
			}
			
			// is the user an expert
			if (!$result && questions_is_expert($container->getContainerEntity(), $user)) {
				$result = true;
			}
		}
	}
	
	return $result;
}
