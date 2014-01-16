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
	
	if (!empty($params) && is_array($params)) {
		$entity = elgg_extract("entity", $params);
		
		if (!empty($entity) && (elgg_instanceof($entity, "object", "question") || elgg_instanceof($entity, "object", "answer"))) {
			if ($entity->canAnnotate(0, "generic_comment")) {
				$items[] = ElggMenuItem::factory(array(
						"name" => "comment",
						"rel" => "toggle",
						"link_class" => "elgg-toggler",
						"href" => "#comments-add-$entity->guid",
						"text" => elgg_view_icon("speech-bubble"),
						"priority" => 600,
				));
			}
			
			if (elgg_instanceof($entity, "object", "answer") && questions_can_mark_answer($entity)) {
				$question = $entity->getContainerEntity();
				$answer = $question->getMarkedAnswer();
				
				if (empty($answer)) {
					$items[] = ElggMenuItem::factory(array(
						"name" => "questions_mark",
						"text" => elgg_echo("questions:menu:entity:answer:mark"),
						"href" => "action/answers/toggle_mark?guid=" . $entity->getGUID(),
						"is_action" => true
					));
				} elseif ($entity->getGUID() == $answer->getGUID()) {
					// there is an anwser and it's this entity
					$items[] = ElggMenuItem::factory(array(
						"name" => "questions_mark",
						"text" => elgg_echo("questions:menu:entity:answer:unmark"),
						"href" => "action/answers/toggle_mark?guid=" . $entity->getGUID(),
						"is_action" => true
					));
				}
			}
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

function questions_container_permissions_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;
	
	if (!$result && !empty($params) && is_array($params)) {
		$question = elgg_extract("container", $params);
		$user = elgg_extract("user", $params);
		$subtype = elgg_extract("subtype", $params);
		
		if (($subtype == "answer") && !empty($user) && elgg_instanceof($question, "object", "question")) {
			$container = $question->getContainerEntity();
			if (elgg_instanceof($container, "user")) {
				$result = true;
			} elseif (elgg_instanceof($container, "group")) {
				// if the user can ask a question in the group, he should be able to answer one too
				$result = $container->canWriteToContainer($user->getGUID(), "object", "question");
			}
		}
	}
	
	return $result;
}

function questions_permissions_handler($hook, $type, $returnvalue, $params) {
	$result = $returnvalue;
	
	// do we have to check further
	if (questions_experts_enabled()) {
		// check if an expert can edit a question
		if (!$result && !empty($params) && is_array($params)) {
			// get the provided data
			$entity = elgg_extract("entity", $params);
			$user = elgg_extract("user", $params);
			
			if (!empty($user) && elgg_instanceof($user, "user") && !empty($entity) && elgg_instanceof($entity, "object", "question")) {
				$container = $entity->getContainerEntity();
				if (!elgg_instanceof($container, "group")) {
					$container = elgg_get_site_entity();
				}
				
				if (questions_is_expert($container, $user)) {
					$result = true;
				}
			}
		}
		
		// an expert should be able to edit an answer, so fix this
		if ($result && !empty($params) && is_array($params)) {
			// get the provided data
			$entity = elgg_extract("entity", $params);
			$user = elgg_extract("user", $params);
				
			if (!empty($user) && elgg_instanceof($user, "user") && !empty($entity) && elgg_instanceof($entity, "object", "answer")) {
				// user is not the owner
				if ($entity->getOwnerGUID() != $user->getGUID()) {
					$question = $entity->getContainerEntity();
					
					if (!empty($question) && elgg_instanceof($question, "object", "question")) {
						$container = $question->getContainerEntity();
						if (!elgg_instanceof($container, "group")) {
							$container = elgg_get_site_entity();
						}
						
						// if the user is an expert
						if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $container->getGUID())) {
							$result = false;
						}
					}
				}
			}
		}
	}
	
	return $result;
}

