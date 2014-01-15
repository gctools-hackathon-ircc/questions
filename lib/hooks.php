<?php

function questions_owner_block_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];

	if ($entity instanceof ElggGroup && $entity->questions_enable != 'no') {
		$items[] = ElggMenuItem::factory(array(
				'name' => 'questions',
				'href' => "/questions/group/$entity->guid/all",
				'text' => elgg_echo('questions:group'),
		));
	} elseif ($entity instanceof ElggUser) {
		$items[] = ElggMenuItem::factory(array(
				'name' => 'questions',
				'href' => "/questions/owner/$entity->username",
				'text' => elgg_echo('questions'),
		));
	}

	return $items;
}

function questions_entity_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];

	if ($entity->getSubtype() == 'question' || $entity->getSubtype() == 'answer') {
		if ($entity->canAnnotate(0, 'generic_comment')) {
			$items[] = ElggMenuItem::factory(array(
					'name' => 'comment',
					'rel' => 'toggle',
					'link_class' => 'elgg-toggler',
					'href' => "#comments-add-$entity->guid",
					'text' => elgg_view_icon('speech-bubble'),
					'priority' => 600,
			));
		}
	}

	return $items;
}

function questions_notify_message_handler($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	$method = $params['method'];

	if (elgg_instanceof($entity, 'object', 'question')) {
		$descr = $entity->description;
		$title = $entity->title;
		$url = $entity->getURL();
		$owner = $entity->getOwnerEntity();
		$via = elgg_echo("questions:via");

		if ($method == 'sms') {
			//shortening the url for sms
			$url = elgg_get_site_url() . "view/$entity->guid";
			return "$owner->name $via: $url ($title)";
		}

		if ($method == 'email') {
			return "$owner->name $via: $title \n\n $descr \n\n $url";
		}

		if ($method == 'web') {
			return "$owner->name $via: $title \n\n $descr \n\n $url";
		}
	}

	return null;
}

function questions_user_hover_menu_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;
	
	// are experts enabled
	if (questions_experts_enabled()) {
		if (!empty($params) && is_array($params)) {
			// get the user for this menu
			$user = elgg_extract("entity", $params);
			
			if (!empty($user) && elgg_instanceof($user, "user") && !$user->isAdmin()) {
				// get page owner
				$page_owner = elgg_get_page_owner_entity();
				if (!elgg_instanceof($page_owner, "group")) {
					$page_owner = elgg_get_site_entity();
				}
				
				// can the current person edit the page owner, to assign the role
				// and is the current user not the owner of this page owner
				if ($page_owner->canEdit() && !$page_owner->canEdit($user->getGUID())) {
					$text = elgg_echo("questions:menu:user_hover:make_expert");
					if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $page_owner->getGUID())) {
						$text = elgg_echo("questions:menu:user_hover:remove_expert");
					}
					
					$result[] = ElggMenuItem::factory(array(
						"name" => "questions_expert",
						"text" => $text,
						"href" => "action/questions/toggle_expert?user_guid=" . $user->getGUID() . "&guid=" . $page_owner->getGUID(),
						"confirm" => elgg_echo("question:areyousure")
					));
				}
			}
		}
	}
	
	return $result;
}
